<?php
namespace app\models;

use DateTime;

class Review{
    public int $id = 0;

    public DateTime $publicationTime;

    public string $fullName;

    public string $email;

    public string $message;

    public function __construct(string $fullName, string $email, string $message)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->message = $message;
    }

    public function isValid(): bool
    {
        return !empty(trim($this->fullName)) && !empty(trim($this->message)) && filter_var($this->email, FILTER_VALIDATE_EMAIL);
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

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Неверный ввод email';
        }
        
        return $errors;
    }
}
?>