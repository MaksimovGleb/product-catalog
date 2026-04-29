<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'string' => 'Поле :attribute должно быть строкой.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'min' => [
        'numeric' => 'Поле :attribute должно быть не меньше :min.',
        'file' => 'Файл :attribute должен быть не меньше :min Кб.',
        'string' => 'Поле :attribute должно быть не меньше :min символов.',
        'array' => 'Поле :attribute должно содержать не меньше :min элементов.',
    ],
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'attributes' => [
        'name' => 'Название',
        'price' => 'Цена',
        'description' => 'Описание',
        'category_id' => 'Категория',
        'email' => 'Email',
        'password' => 'Пароль',
    ],
];
