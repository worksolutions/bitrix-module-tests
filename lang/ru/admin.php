<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
return array(
    'list' => array(
        'title' => 'Список тестов',
        'columns' => array(
            'name' => 'Название',
            'type' => 'Тип теста',
            'labels' => 'Метки',
            'run' => 'Прохождение'
        ),
        'actions' => array(
            'add' => 'Добавить тест кейс',
            'run' => 'Запустить',
            'history' => 'История',
            'report' => 'Отчет',
            'edit' => 'Редактировать',
            'download' => 'Скачать тест кейсы',
        ),
        'messages' => array(
            'type' => array(
                'all' => 'Все',
                'auto' => 'Автотесты',
                'manual' => 'Тесткейсы'
            )
        )
    ),
    'edit' => array(
        'title' => 'Добавление/Редактирование тест кейса',
        'context-menu' => array(
            'back' => 'Список тестов'
        ),
        'messages' => array(
            'no' => 'Нет',
            'name' => 'Название',
            'labels' => 'Метки',
            'state' => 'Подготовка состояния',
            'scenario' => 'Сценарий тестирования',
            'result' => 'Результат тестирования',
            'add' => 'Добавить',
        ),
        'case' => array(
            'step' => 'Шаг',
            'action' => 'Действие',
            'result' => 'Результат',
        ),
        'errors' => array(
            'message' => 'Сообщение об ошибке',
            'name-empty' => 'Название теста должно быть заполнено',
            'scenario-empty' => 'Сценарий тестирования должен быть заполнен',
            'result-empty' => 'Результат тестирования должен быть заполнен'
        )
    )
);