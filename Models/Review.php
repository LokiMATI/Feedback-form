<?php
namespace app\models;

use DateTime;

class Review{
    public int $id = 0;

    public DateTime $publicationTime;

    public string $fullName;

    public string $message;

    public function __construct(string $FullName = '', string $message = '')
    {
        $this->fullName = $FullName;
        $this->message = $message;
    }

    public function isValid(): bool
    {
        return !empty(trim($this->fullName)) && !empty(trim($this->message));
    }
    
    public function getValidationErrors(): array
    {
        $errors = [];
        
        if (empty(trim($this->fullName))) {
            $errors[] = 'Имя не может быть пустым';
        }
        
        if (empty(trim($this->message))) {
            $errors[] = 'Сообщение не может быть пустым';
        }
        
        if (strlen(trim($this->fullName)) > 100) {
            $errors[] = 'Имя слишком длинное (максимум 100 символов)';
        }
        
        return $errors;
    }
}
?>