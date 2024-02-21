<?php
namespace App\Tests\Unit\Service;

use App\Entity\FormField;
use App\FieldTypes\FieldTypesFabric;
use App\Repository\SubmissionRepository;
use App\Service\FormFieldService;
use App\Service\FormFieldTypeService;
use App\Service\FormSubmissionService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class FormSubmissionServiceTest extends TestCase 
{
  public function testDateFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('date');
    $formField->setName('date');
    $formField->setValidations([]);

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      '2021-01-01' => true,
      '2021-01-01 00:00:00' => false,
      '2021-01-01 00:00' => false,
      '2021-01-01 00' => false,
      '2021-01-01 00:00:00.000' => false,
      '20210101' => false,
      '2021/01/01' => false,
      '3056-12-12' => true,
    ];

    foreach ($tests as $date => $expected) {
      $result = $formSubmissionService->submit(1, ['date' => $date]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testDecimalFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('decimal');
    $formField->setName('decimal');
    $formField->setValidations([
      [
        'name' => 'min',
        'type' => 'int',
        'value' => 0,
      ],
      [
        'name' => 'max',
        'type' => 'int',
        'value' => 1000,
      ],
      [
        'name' => 'precision',
        'type' => 'int',
        'value' => 2,
      ]
    ]);

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      '0' => false,
      '0.00' => true,
      '0.000' => false,
      '000000.000' => false,
      '000000.00' => true,
      '999.98' => true,
      '1000.01' => false,
    ];

    foreach ($tests as $decimal => $expected) {
      $result = $formSubmissionService->submit(1, ['decimal' => $decimal]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testEmailFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('email');
    $formField->setName('email');
    $formField->setValidations([]);

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      '' => false, 
      '' => false,
      'email' => false,
      'email@' => false,
      'email@domain' => false,
      'email@domain.' => false,
      'email@email.com' => true,
      'sdfkjsdf@email.app' => true,
      'some-email.name@gmail.com' => true,
      'some-email.name@gmail.eu' => true,
    ];
    
    foreach ($tests as $email => $expected) {
      $result = $formSubmissionService->submit(1, ['email' => $email]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testIntegerFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('integer');
    $formField->setName('integer');
    $formField->setValidations(
      [
        [
          'name' => 'min',
          'type' => 'int',
          'value' => 5,
        ],
        [
          'name' => 'max',
          'type' => 'int',
          'value' => 10,
        ],
      ]
    );

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      0 => false,
      1 => false,
      6 => true,
      20 => false,
    ];

    foreach ($tests as $integer => $expected) {
      $result = $formSubmissionService->submit(1, ['integer' => $integer]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testNumberFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('number');
    $formField->setName('number');
    $formField->setValidations(
      [
        [
          'name' => 'min',
          'type' => 'int',
          'value' => 0,
        ],
        [
          'name' => 'max',
          'type' => 'int',
          'value' => 10000,
        ],
      ]
    );

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      6 => true,
      9.999 => true,
      5666.898 => true,
      '5666.898' => true,
      'sdfkjhskdhf' => false,
    ];

    foreach ($tests as $number => $expected) {
      $result = $formSubmissionService->submit(1, ['number' => $number]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testOptionsFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('options');
    $formField->setName('options');
    $formField->setValidations(
      [
        [
          'name' => 'options',
          'type' => 'array',
          'value' => [
            'option1',
            'option2',
            'option3',
          ],
        ],
      ]
    );

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      [
        ['option1'],
        true,
      ],
      [
        ['option1', 'option2'],
        true,
      ],
      [
        ['option1', 'option2', 'option3'],
        true,
      ],
      [
        ['option1', 'option2', 'option3', 'option4'],
        false,
      ],
      [
        ['option1', 'option2', 'option3', 'option4', 'option5'],
        false,
      ],
    ];

    foreach ($tests as $test) {
      $result = $formSubmissionService->submit(1, ['options' => $test[0]]);
      $this->assertEquals($test[1], $result['success']);
    }
  }

  public function testTelFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('tel');
    $formField->setName('tel');
    $formField->setValidations([]);

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      '' => false,
      '123' => false,
      '1234567890' => false,
      '+1234567890' => true,
    ];

    foreach ($tests as $tel => $expected) {
      $result = $formSubmissionService->submit(1, ['tel' => $tel]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testTextFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('text');
    $formField->setName('text');
    $formField->setValidations(
      [
        [
          'name' => 'minLength',
          'type' => 'int',
          'value' => 10,
        ],
        [
          'name' => 'maxLength',
          'type' => 'int',
          'value' => 20,
        ],
      ]
    );

    $formSubmissionService = $this->createSubmissionService([$formField]);

    $tests = [
      'SmallSmal' => false,
      'SmallSmalSmallSmaliu' => true,
      'BigBigBigBigBigBigBg' => true,
      'BigBigBigBigBigBigBig' => false,
    ];

    foreach ($tests as $text => $expected) {
      $result = $formSubmissionService->submit(1, ['text' => $text]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testTimeFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('time');
    $formField->setName('time');
    $formField->setValidations([]);

    $formSubmissionService = $this->createSubmissionService([$formField]);
    
    $tests = [
      '00:00' => true,
      '00' => false,
      '00:00:00.000' => false,
      '000000' => false,
      '000000.000' => false,
      '000000.00' => false,
      '000000.0' => false,
    ];

    foreach ($tests as $text => $expected) {
      $result = $formSubmissionService->submit(1, ['time' => $text]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  public function testUrlFieldTypeValidation()
  {
    $formField = new FormField();
    $formField->setType('url');
    $formField->setName('url');
    $formField->setValidations([]);

    $formFieldService = $this->mockFormFieldService([$formField]);

    $formSubmissionService = new FormSubmissionService(
      $formFieldService,
      new FormFieldTypeService(new FieldTypesFabric()),
      $this->mockSubmissionRepository(),
      $this->mockEventDispatcher(),
    );
    
    $tests = [
      '' => false,
      'url' => false,
      'url.' => false,
      'http://url.com' => true,
      'https://url.eu' => true,
      'http://url.app' => true,
      'https://url.name' => true,
      'http://url.name.com' => true,
      'ftp://url.name.eu' => true,
      'url.name.app' => false,
    ];

    foreach ($tests as $text => $expected) {
      $result = $formSubmissionService->submit(1, ['url' => $text]);
      $this->assertEquals($expected, $result['success']);
    }
  }

  private function mockFormFieldService(array $formFields = [])
  {
    $formFieldService = $this->createMock(FormFieldService::class);
    $formFieldService->method('getAllByFormId')->willReturn($formFields);
    return $formFieldService;
  }

  private function mockSubmissionRepository()
  {
    $submissionRepository = $this->createMock(SubmissionRepository::class);
    $submissionRepository->method('create')->willReturn(null);
    return $submissionRepository;
  }

  private function mockEventDispatcher()
  {
    $mock = $this->createMock(EventDispatcher::class);
    $mock->method('dispatch')->willReturn((object)[]);

    return $mock;
  }

  private function createSubmissionService(array $formFields): FormSubmissionService
  {
    return new FormSubmissionService(
      $this->mockFormFieldService($formFields),
      new FormFieldTypeService(new FieldTypesFabric()),
      $this->mockSubmissionRepository(),
      $this->mockEventDispatcher(),
    );
  }
}