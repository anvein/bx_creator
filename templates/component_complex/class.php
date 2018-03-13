<?php

namespace components\anvein;

use Exception;
use InvalidArgumentException;
use CUser;
use CEventLog;
use CIBlockRights;
use CBitrixComponent;
use Bitrix\Main\Loader;
use CComponentEngine;
use Bitrix\Main\Application;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Security\SecurityException;

class ComponentComplex extends CBitrixComponent
{
    /**
     * {@inheritdoc}
     */
    public function onPrepareComponentParams($params)
    {
        $params = parent::onPrepareComponentParams($params);
        $params['PAGE_404'] = !empty($params['PAGE_404']) ? $params['PAGE_404'] : '/404.php';
        $params['SEF_FOLDER'] = !empty($params['SEF_FOLDER']) ? $params['SEF_FOLDER'] : [];
        $params['USER_RULES'] = !empty($params['USER_RULES']) ? $params['USER_RULES'] : [];
        $params['SEF_URL_TEMPLATES'] = !empty($params['SEF_URL_TEMPLATES']) ? $params['SEF_URL_TEMPLATES'] : [];
        $params['VARIABLE_ALIASES'] = !empty($params['VARIABLE_ALIASES']) ? $params['VARIABLE_ALIASES'] : [];

        // TODO: обработать входящие параметры компонента

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function executeComponent()
    {
        global $USER;
        $userId = $USER->GetId();

        try {
            $this->arResult = $this->useSef(
                $this->arParams['SEF_FOLDER'],
                $this->arParams['SEF_URL_TEMPLATES'],
                $this->arParams['VARIABLE_ALIASES']
            );
            $this->arResult['CURRENT_USER'] = $userId;
            $this->arResult['CURRENT_USER_IS_ADMIN'] = $USER->isAdmin();
            $canAccess = $this->arResult['CURRENT_USER_IS_ADMIN'] ||
                $this->canUserAccess($userId, $this->arResult['PAGE'], $this->arParams['USER_RULES']);
            $pageExists = !empty($this->arResult['PAGE']);

            if ($pageExists) {
                $this->IncludeComponentTemplate($this->arResult['PAGE']);
            } elseif (!$canAccess) {
                throw new SecurityException('Access denied');
            } else {
                $this->show404();
                //throw new ObjectNotFoundException('Page not found');
            }
        } catch (Exception $e) {
            $this->handleException($e, $userId);
        }
    }

    /**
     * Обрабатывает адрес на основании ЧПУ.
     *
     * @param string $folder
     * @param array  $sefUrlTemplates
     * @param array  $sefVariableAliases
     *
     * @return array
     */
    protected function useSef($folder, array $sefUrlTemplates, array $sefVariableAliases)
    {
        $method = Application::getInstance()->getContext()->getRequest()->getRequestMethod();
        $methodFilteredSefUrlTemplates = [];
        foreach ($sefUrlTemplates as $key => $value) {
            $arExplode = explode(':', $value);
            if (count($arExplode) > 1) {
                $currentMethod = strtoupper(trim($arExplode[0]));
                $url = $arExplode[1];
                if (!in_array($currentMethod, ['GET', 'POST', 'PUT', 'PATCH', 'HEAD', 'DELETE'])) {
                    throw new InvalidArgumentException("Wrong action for page {$value}");
                }
                if ($currentMethod === $method) {
                    $methodFilteredSefUrlTemplates[$key] = $url;
                }
            } else {
                $url = $arExplode[0];
                $methodFilteredSefUrlTemplates[$key] = $url;
            }
        }

        $arVariables = [];
        $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates([], $methodFilteredSefUrlTemplates);
        $arVariableAliases = CComponentEngine::MakeComponentVariableAliases([], $sefVariableAliases);
        $engine = new CComponentEngine($this);
        if (Loader::IncludeModule('iblock')) {
            $engine->addGreedyPart('#SECTION_CODE_PATH#');
            $engine->setResolveCallback(['CIBlockFindTools', 'resolveComponentEngine']);
        }
        $componentPage = $engine->guessComponentPath(
            $folder,
            $arUrlTemplates,
            $arVariables
        );
        global $APPLICATION;
        if (!$componentPage && rtrim($folder, '/') === rtrim($APPLICATION->GetCurPage(), '/')) {
            foreach ($methodFilteredSefUrlTemplates as $key => $tpl) {
                if ($tpl !== '') {
                    continue;
                }
                $componentPage = $key;
                break;
            }
        }
        CComponentEngine::InitComponentVariables(
            $componentPage,
            $arComponentVariables,
            $arVariableAliases,
            $arVariables
        );

        return [
            'FOLDER' => $folder,
            'URL_TEMPLATES' => $arUrlTemplates,
            'VARIABLES' => $arVariables,
            'ALIASES' => $arVariableAliases,
            'PAGE' => $componentPage,
        ];
    }

    /**
     * Проверяет имеет ли указанный пользователь доступ к указанной странице компонента,
     * на основании переданных в третьем параметре правил.
     *
     * @param $userId
     * @param $page
     * @param $rules
     *
     * @return bool|mixed
     *
     * @throws SecurityException
     */
    protected function canUserAccess($userId, $page, $rules)
    {
        $return = true;

        if (isset($rules[$page])) {
            if ($return && isset($rules[$page]['is_authorized'])) {
                $return = ($rules[$page]['is_authorized'] === true && $userId)
                    || ($rules[$page]['is_authorized'] === false && !$userId);
                if (!$return && $rules[$page]['is_authorized'] === true) {
                    throw new SecurityException('Need authorization');
                }
            }
            if ($return && isset($rules[$page]['callback'])) {
                $return = call_user_func_array($rules[$page]['callback'], [
                    $userId,
                    $this,
                ]);
            }
            if ($return && isset($rules[$page]['operations'])) {
                $return = $this->doesUserCanDoOperation($userId, $rules[$page]['operations']);
            }
            if ($return && isset($rules[$page]['iblock_operations'])) {
                $return = $this->doesUserCanDoIblockOperation($userId, $rules[$page]['iblock_operations']);
            }
            if ($return && isset($rules[$page]['iblock_section_operations'])) {
                $return = $this->doesUserCanDoIblockSectionOperation($userId, $rules[$page]['iblock_section_operations']);
            }
        }

        return $return;
    }

    /**
     * Может ли пользователь выполнять хотя бы одну из указанных операций.
     *
     * @param int   $userId
     * @param array $operations
     *
     * @return bool
     */
    protected function doesUserCanDoOperation($userId, array $operations)
    {
        $return = false;
        if ($userId) {
            $user = new CUser;
            foreach ($operations as $operation) {
                if (!$user->CanDoOperation($operation, $userId)) {
                    continue;
                }
                $return = true;
                break;
            }
        }

        return $return;
    }

    /**
     * Может ли пользователь выполнять хотя бы одну из указанных операций для соответствующего инфоблока.
     *
     * @param int   $userId
     * @param array $operations
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    protected function doesUserCanDoIblockOperation($userId, array $operations)
    {
        $return = false;
        if (!array_key_exists('iblock', $operations)) {
            throw new InvalidArgumentException('Param iblock must be set for iblock_operations rule');
        } elseif (!array_key_exists('operations', $operations) || !is_array($operations['operations'])) {
            throw new InvalidArgumentException('Param operations must be set for iblock_operations rule and must be an array instance');
        } elseif ($userId) {
            foreach ($operations['operations'] as $operation) {
                $res = CIBlockRights::UserHasRightTo(
                    $operations['iblock'],
                    $operations['iblock'],
                    $operation
                );
                if (!$res) {
                    continue;
                }
                $return = true;
                break;
            }
        }

        return $return;
    }

    /**
     * Может ли пользователь выполнять хотя бы одну из указанных операций для соответствующей секции инфоблока.
     *
     * @param int   $userId
     * @param array $operations
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    protected function doesUserCanDoIblockSectionOperation($userId, array $operations)
    {
        $return = false;
        if (!array_key_exists('iblock', $operations) || !array_key_exists('section', $operations)) {
            $paramName = !array_key_exists('iblock', $operations) ? 'iblock' : 'section';
            throw new InvalidArgumentException("Param {$paramName} must be set for iblock_section_operations rule");
        } elseif (!array_key_exists('operations', $operations) || !is_array($operations['operations'])) {
            throw new InvalidArgumentException('Param operations must be set for iblock_section_operations rule and must be an array instance');
        } elseif ($userId) {
            foreach ($operations['operations'] as $operation) {
                $res = CIBlockRights::UserHasRightTo(
                    $operations['iblock'],
                    $operations['iblock'],
                    $operation
                );
                if (!$res) {
                    continue;
                }
                $return = true;
                break;
            }
        }

        return $return;
    }

    /**
     * Обрабатывает исключение.
     *
     * @param Exception $e
     * @param int       $userId
     */
    protected function handleException(Exception $e, $userId)
    {
        CEventLog::Add([
            'SEVERITY' => 'WARNING',
            'AUDIT_TYPE_ID' => 'api_component_exception',
            'MODULE_ID' => 'main',
            'ITEM_ID' => get_class($this),
            'DESCRIPTION' => json_encode([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'arResult' => $this->arResult,
                'arParams' => $this->arParams,
            ], JSON_UNESCAPED_UNICODE),
        ]);

        if ($e instanceof SecurityException) {
            if (!$userId && !empty($this->arParams['AUTH_URL'])) {
                LocalRedirect($this->arParams['AUTH_URL']);
            }
            Application::getInstance()->getContext()->getResponse()->setStatus('404 Not Found');
        } else {
            Application::getInstance()->getContext()->getResponse()->setStatus('500 Internal Server Error');
        }
        $this->show404();
    }

    /**
     * Отображает страницу 404.
     */
    protected function show404()
    {
        Tools::process404('', true, false, true, $this->arParams['PAGE_404']);
    }
}
