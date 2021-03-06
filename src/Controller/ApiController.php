<?php

namespace App\Controller;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends Controller
{
    /** @var ValidatorInterface $validator */
    protected $validator;

    /**
     * Controller constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $fields
     * @return Collection
     */
    protected function getValidationCollection(array $fields): Collection
    {
        return new Collection([
            'fields' => $fields,
            'missingFieldsMessage' => 'Field {{ field }} not found',
            'extraFieldsMessage' => 'Field {{ field }} was not expected',
        ]);
    }
}