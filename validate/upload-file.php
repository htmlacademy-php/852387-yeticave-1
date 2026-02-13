<?php
declare(strict_types=1);

const FILE_TYPES = ['image/jpeg', 'image/png'];

/**
 * Проверка загрузки файла пользователем с выводом ошибки и пути файла
 * @param array $file Переменные файла, загруженного по HTTP
 * @return null[]|string[] [ошибка, путь файла]
 */
function validate_upload_file(array $file): array
{
    $error_file = null;
    $path_file = null;

    if (empty($file['name'])) {
        $error_file = 'Вы не загрузили файл';
    } else {
        $tmp_name = $file['tmp_name'];
        $file_type = mime_content_type($tmp_name);

        if (!in_array($file_type, FILE_TYPES, true)) {
            $error_file = 'Неверный формат файла. Загрузите файл в формате JPG, JPEG или PNG';
        } else {
            $extension = pathinfo($tmp_name, PATHINFO_EXTENSION);
            $file_name = uniqid('', true) . '852387-yeticave-1' . $extension;

            move_uploaded_file($tmp_name, 'uploads/' . $file_name);
            $path_file = '/uploads/' . $file_name;
        }
    }
    return [$error_file, $path_file];
}
