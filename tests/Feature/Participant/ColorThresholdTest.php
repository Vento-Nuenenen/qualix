<?php

namespace Tests\Feature\Participant;

use App\Models\Course;
use App\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCaseWithBasicData;

class ColorThresholdTest extends TestCaseWithBasicData {

    public function test_shouldPassThresholdsToOverviewComponent() {
        // given
        $course = Course::find($this->courseId);
        $course->update(['observation_count_red_threshold' => 3, 'observation_count_green_threshold' => 7]);

        // when
        $response = $this->get('/course/' . $this->courseId . '/participants/' . $this->participantId);

        // then
        $response->assertOk();
        $response->assertSee(':red-threshold="3"', false);
        $response->assertSee(':green-threshold="7"', false);
    }
}
