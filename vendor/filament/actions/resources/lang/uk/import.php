<?php

return [

    'label' => 'Імпорт :label',

    'modal' => [

        'heading' => 'Імпорт :label',

        'form' => [

            'file' => [
                'label' => 'Файл',
                'placeholder' => 'Завантажити CSV-файл',
            ],

            'columns' => [
                'label' => 'Стовпці',
                'placeholder' => 'Виберіть стовпець',
            ],

        ],

        'actions' => [

            'download_example' => [
                'label' => 'Завантажити приклад CSV-файлу',
            ],

            'import' => [
                'label' => 'Імпорт',
            ],

        ],

    ],

    'notifications' => [

        'completed' => [

            'title' => 'Імпорт завершено',

            'actions' => [

                'download_failed_rows_csv' => [
                    'label' => 'Завантажити інформацію про невдалий рядок|Завантажити інформацію про невдалий рядок',
                ],

            ],

        ],

        'max_rows' => [
            'title' => 'Завантажений файл CSV занадто великий.',
            'body' => 'Ви не можете імпортувати більше 1 рядка одночасно.|Ви не можете імпортувати більше :count рядків одночасно.',
        ],

        'started' => [
            'title' => 'Імпорт розпочався',
            'body' => 'Ваш імпорт розпочався, і 1 рядок буде опрацьовано у фоновому режимі.|Ваш імпорт розпочався, і :count рядків буде опрацьовуватися у фоновому режимі.',
        ],

    ],

    'example_csv' => [
        'file_name' => ':importer-example',
    ],

    'failure_csv' => [
        'file_name' => 'import-:import_id-:csv_name-failed-rows',
        'error_header' => 'Помилка',
        'system_error' => 'Системна помилка, зверніться до служби підтримки.',
    ],

];
