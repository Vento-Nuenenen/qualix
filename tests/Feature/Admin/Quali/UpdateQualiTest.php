<?php

namespace Tests\Feature\Admin\Quali;

use App\Models\Course;
use App\Models\Quali;
use App\Models\QualiData;
use App\Services\TiptapFormatter;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCaseWithBasicData;

class UpdateQualiTest extends TestCaseWithBasicData {

    private $course;
    private $payload;
    private $qualiId;
    private $qualiDataId;

    public function setUp(): void {
        parent::setUp();

        $this->course = Course::find($this->courseId);
        $this->qualiId = $this->createQuali('Zwischequali');
        $this->qualiDataId = Quali::find($this->qualiId)->quali_data_id;

        $this->payload = [
            'name' => 'Zwischenquali',
            'participants' => $this->course->participants()->pluck('id')->implode(','),
            'requirements' => $this->course->requirements()->pluck('id')->implode(','),
        ];
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $this->payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldUpdateAndDisplayQuali() {
        // given

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $this->payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        /** @var TestResponse $response */
        $response = $response->followRedirects();
        $response->assertSee($this->payload['name']);
    }

    public function test_shouldUpdateQuali_usesTiptapFormatter_forUpdatingQualiContent_whenRequirementsChanged() {
        // given
        $payload = $this->payload;
        $requirementId = $this->createRequirement('Zusätzliche Anforderung');
        $payload['requirements'] = implode(',', array_filter([$payload['requirements'], $requirementId]));
        $mock = Mockery::mock(TiptapFormatter::class, function ($mock) {
            $mock->shouldReceive('appendRequirementsToQuali')
                ->once()
                ->with(Mockery::type(Collection::class));
        })->makePartial();
        $this->app->extend(TiptapFormatter::class, function() use ($mock) { return $mock; });

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
    }

    public function test_shouldValidateNewQualiData_noName() {
        // given
        $payload = $this->payload;
        unset($payload['name']);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Titel muss ausgefüllt sein.', $exception->validator->errors()->first('name'));
    }

    public function test_shouldValidateNewQualiData_longName() {
        // given
        $payload = $this->payload;
        $payload['name'] = 'Zwischenquali, welches im Grunde genommen auch ohne Qualix geführt werden könnte, obwohl man natürlich hoffen würde dass Qualix einen bei der Kursführung unterstützen sollte, was wohl auch der Grund ist dass nun in diesem Kurs das endlich verfügbare Quali-Feature eingesetzt werden soll... Tja.';

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Titel darf maximal 255 Zeichen haben.', $exception->validator->errors()->first('name'));
    }

    public function test_shouldValidateNewQualiData_noParticipantIds() {
        // given
        $payload = $this->payload;
        $payload['participants'] = null;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('TN muss ausgefüllt sein.', $exception->validator->errors()->first('participants'));
    }

    public function test_shouldValidateNewQualiData_invalidParticipantIds() {
        // given
        $payload = $this->payload;
        $payload['participants'] = 'xyz';

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('TN Format ist ungültig.', $exception->validator->errors()->first('participants'));
    }

    public function test_shouldValidateNewQualiData_oneValidParticipantId() {
        // given
        $payload = $this->payload;
        $participantId = $this->createParticipant();
        $payload['participants'] = $participantId;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals([$participantId], QualiData::latest()->first()->qualis()->pluck('participant_id')->all());
    }

    public function test_shouldValidateNewQualiData_multipleValidParticipantIds() {
        // given
        $payload = $this->payload;
        $participantIds = [$this->createParticipant(), $this->createParticipant()];
        $payload['participants'] = implode(',', $participantIds);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals($participantIds, QualiData::latest()->first()->qualis()->pluck('participant_id')->all());
    }

    public function test_shouldValidateNewQualiData_someNonexistentParticipantIds() {
        // given
        $payload = $this->payload;
        $participantIds = [$this->createParticipant(), '999999', $this->createParticipant()];
        $payload['participants'] = implode(',', $participantIds);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Der gewählte Wert für TN ist ungültig.', $exception->validator->errors()->first('participants'));
    }

    public function test_shouldValidateNewQualiData_someInvalidParticipantIds() {
        // given
        $payload = $this->payload;
        $participantIds = [$this->createParticipant(), 'abc', $this->createParticipant()];
        $payload['participants'] = implode(',', $participantIds);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('TN Format ist ungültig.', $exception->validator->errors()->first('participants'));
    }

    public function test_shouldValidateNewQualiData_noRequirementIds() {
        // given
        $payload = $this->payload;
        $payload['requirements'] = null;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals([], Quali::latest()->first()->requirements()->pluck('requirements.id')->all());
    }

    public function test_shouldValidateNewQualiData_invalidRequirementIds() {
        // given
        $payload = $this->payload;
        $payload['requirements'] = 'xyz';

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Relevante Anforderungen Format ist ungültig.', $exception->validator->errors()->first('requirements'));
    }

    public function test_shouldValidateNewQualiData_oneValidRequirementId() {
        // given
        $payload = $this->payload;
        $requirementId = $this->createRequirement();
        $payload['requirements'] = $requirementId;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals([$requirementId], Quali::find($this->qualiId)->requirements()->pluck('requirements.id')->all());
    }

    public function test_shouldValidateNewQualiData_multipleValidRequirementIds() {
        // given
        $payload = $this->payload;
        $requirementIds = [$this->createRequirement(), $this->createRequirement()];
        $payload['requirements'] = implode(',', $requirementIds);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals($requirementIds, Quali::find($this->qualiId)->requirements()->pluck('requirements.id')->all());
    }

    public function test_shouldValidateNewQualiData_someNonexistentRequirementIds() {
        // given
        $payload = $this->payload;
        $requirementIds = [$this->createRequirement(), 999999, $this->createRequirement()];
        $payload['requirements'] = implode(',', $requirementIds);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Der gewählte Wert für Relevante Anforderungen ist ungültig.', $exception->validator->errors()->first('requirements'));
    }

    public function test_shouldValidateNewQualiData_someInvalidRequirementIds() {
        // given
        $payload = $this->payload;
        $requirementIds = [$this->createRequirement(), 'abc', $this->createRequirement()];
        $payload['requirements'] = implode(',', $requirementIds);

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Relevante Anforderungen Format ist ungültig.', $exception->validator->errors()->first('requirements'));
    }

    public function test_shouldValidateNewQualiData_invalidTrainerAssignment() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $payload['qualis'][$participantId]['users'] = 'abc';

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Zuständig für Pflock Format ist ungültig.', $exception->validator->errors()->first("qualis.$participantId.users"));
    }

    public function test_shouldValidateNewQualiData_nonexistentTrainerAssignment() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $payload['qualis'][$participantId]['users'] = 999999;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Der gewählte Wert für Zuständig für Pflock ist ungültig.', $exception->validator->errors()->first("qualis.$participantId.users"));
    }

    public function test_shouldValidateNewQualiData_assigningTrainerFromOtherCourse() {
        // given
        $user2 = $this->createUser();
        $user2->courses()->attach($this->createCourse('Zweiter Kurs'));

        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $payload['qualis'][$participantId]['users'] = $user2->id;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Der gewählte Wert für Zuständig für Pflock ist ungültig.', $exception->validator->errors()->first("qualis.$participantId.users"));
    }

    public function test_shouldValidateNewQualiData_existingTrainerAssignment_shouldWork() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $userId = $this->course->users()->pluck('id')->first();
        $payload['qualis'][$participantId]['users'] = $userId;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals($userId, Quali::find($this->qualiId)->users()->pluck('id')->join(','));
    }

    public function test_shouldValidateNewQualiData_multipleExistingTrainerAssignment_shouldWork() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $user2 = $this->createUser();
        $user2->courses()->attach($this->course);
        $userIds = $this->course->users()->pluck('id')->first();
        $payload['qualis'][$participantId]['users'] = $userIds;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals($userIds, Quali::find($this->qualiId)->users()->pluck('id')->join(','));
    }

    public function test_shouldValidateNewQualiData_someInvalidTrainerAssignments() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $userIds = $this->course->users()->pluck('id')->first() . ',a';
        $payload['qualis'][$participantId]['users'] = $userIds;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Zuständig für Pflock Format ist ungültig.', $exception->validator->errors()->first("qualis.$participantId.users"));
    }

    public function test_shouldValidateNewQualiData_someNonexistentTrainerAssignments() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $userIds = $this->course->users()->pluck('id')->first() . ',9999999';
        $payload['qualis'][$participantId]['users'] = $userIds;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Der gewählte Wert für Zuständig für Pflock ist ungültig.', $exception->validator->errors()->first("qualis.$participantId.users"));
    }

    public function test_shouldValidateNewQualiData_someForeignTrainerAssignments() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $user2 = $this->createUser();
        $user2->courses()->attach($this->createCourse('Other course'));
        $userIds = $this->course->users()->pluck('id')->first() . ',' . $user2->id;
        $payload['qualis'][$participantId]['users'] = $userIds;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Der gewählte Wert für Zuständig für Pflock ist ungültig.', $exception->validator->errors()->first("qualis.$participantId.users"));
    }

    public function test_shouldValidateNewQualiData_removePreviousTrainerAssignment_shouldWork() {
        // given
        $payload = $this->payload;
        $participantId = $this->course->participants()->pluck('id')->first();
        $userId = $this->course->users()->pluck('id')->first();
        Quali::find($this->qualiId)->users()->attach($userId);
        $payload['qualis'][$participantId]['users'] = null;

        // when
        $response = $this->post('/course/' . $this->courseId . '/admin/qualis/' . $this->qualiDataId, $payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/qualis');
        $this->assertEquals(null, Quali::find($this->qualiId)->users()->pluck('id')->join(','));
    }
}
