@extends('layouts.default')

@section('content')

    <b-card>
        <template #header>{{__('t.views.admin.qualis.new')}}</template>

        <form-quali-data
            :action="['admin.qualis.store', { course: {{ $course->id }} }]"
            :participants="{{ json_encode($course->participants->map->only('id', 'scout_name')) }}"
            :participant-groups="{{json_encode(
                $course->participantGroups->mapWithKeys(function ($group) {
                    return [$group['group_name'] => $group->participants->pluck('id')->join(',')];
                }), JSON_FORCE_OBJECT)}}"
            :requirements="{{ json_encode($course->requirements->map->only('id', 'content')) }}"
            :trainers="{{ json_encode($course->users->map->only('id', 'name')) }}"
            quali-notes-template>

            <template #submit>
                <button-submit label="{{__('t.views.admin.qualis.create')}}">
                    @component('components.help-text', ['id' => 'qualiHelp', 'key' => 't.views.admin.qualis.what_are_qualis'])@endcomponent
                </button-submit>
            </template>

        </form-quali-data>

    </b-card>

    <b-card>
        <template #header>{{__('t.views.admin.qualis.existing', ['courseName' => $course->name])}}</template>

        @if (count($course->quali_datas))

            <responsive-table
                :data="{{ json_encode($course->quali_datas) }}"
                :fields="[
                    { label: $t('t.models.quali.name'), value: qualiData => qualiData.name },
                ]"
                :actions="{
                    edit: qualiData => routeUri('admin.qualis.edit', {course: {{ $course->id }}, quali_data: qualiData.id}),
                    delete: qualiData => ({
                        text: $t('t.views.admin.qualis.really_delete', qualiData),
                        route: ['admin.qualis.delete', {course: {{ $course->id }}, quali_data: qualiData.id}]
                    })
                }"></responsive-table>

        @else

            {{__('t.views.admin.qualis.no_qualis')}}

        @endif

    </b-card>

@endsection
