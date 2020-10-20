<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

class QualiUpdateRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => 'required|max:255',
            'participants' => 'required|regex:/^\d+(,\d+)*$/|allExistInCourse',
            'requirements' => 'nullable|regex:/^\d+(,\d+)*$/|allExistInCourse',
            'quali_contents_template' => 'nullable|validQualiContentWithoutObservations',
            'qualis' => 'nullable|array',
            'qualis.*.user' => 'nullable|regex:/^\d+$/|existsInCourse:trainers,user_id',
        ];
    }

    public function attributes() {
        /** @var Collection $participantNames */
        $participantNames = $this->route('course')->participants->mapWithKeys(function ($participant) {
            return ['qualis.'.$participant->id.'.user' => trans('t.models.quali.trainer_assignment', ['participant' => $participant->scout_name])];
        });
        return array_merge(Lang::get('t.models.quali'), $participantNames->all());
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function prepareForValidation() {
        parent::prepareForValidation();
        $this->merge(['quali_contents_template' => json_decode($this->get('quali_contents_template'), true)]);
    }
}
