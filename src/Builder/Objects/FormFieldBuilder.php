<?php

namespace Riddle\Api\Builder\Objects;

/**
 * This sets up the data object for a Riddle form builder.
 * @see https://www.riddle.com/help/api/build-riddles/form-fields
 */
class FormFieldBuilder
{
    private array $fields = [];
    private string $title = 'My form';

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function addNameField(string $label = 'Name'): static
    {
        return $this->addFormField('Name', $label);
    }

    public function addEmailField(string $label = 'Email'): static
    {
        return $this->addFormField('Email', $label);
    }

    public function addPhoneField(string $label = 'Phone'): static
    {
        return $this->addFormField('Phone', $label);
    }

    public function addUrlField(string $label = 'URL'): static
    {
        return $this->addFormField('URL', $label);
    }

    public function addNumberField(string $label = 'Number'): static
    {
        return $this->addFormField('Number', $label);
    }

    public function addCountryField(string $label = 'Country'): static
    {
        return $this->addFormField('Country', $label);
    }

    public function addShortTextField(string $label = 'ShortText'): static
    {
        return $this->addFormField('ShortText', $label);
    }

    public function addLongTextField(string $label = 'LongText'): static
    {
        return $this->addFormField('LongText', $label);
    }

    private function addFormField(string $type, string $label): static
    {
        $this->fields[$label] = $type;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => 'FormBuilder',
            'fields' => $this->fields,
        ];
    }
}