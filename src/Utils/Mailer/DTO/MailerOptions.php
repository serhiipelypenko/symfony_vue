<?php

namespace App\Utils\Mailer\DTO;

class MailerOptions
{
    private string $recipient;
    private string $cc;
    private string $subject;
    private string $htmlTemplate;
    private array $context;
    private string $text;

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): MailerOptions
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getCc(): string
    {
        return $this->cc;
    }

    public function setCc(string $cc): MailerOptions
    {
        $this->cc = $cc;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): MailerOptions
    {
        $this->subject = $subject;
        return $this;
    }

    public function getHtmlTemplate(): string
    {
        return $this->htmlTemplate;
    }

    public function setHtmlTemplate(string $htmlTemplate): MailerOptions
    {
        $this->htmlTemplate = $htmlTemplate;
        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): MailerOptions
    {
        $this->context = $context;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): MailerOptions
    {
        $this->text = $text;
        return $this;
    }
}
