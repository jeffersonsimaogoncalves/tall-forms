<?php


namespace Tanthammar\TallForms\Components;

use Illuminate\View\View;
use Illuminate\View\Component;
use Tanthammar\TallForms\Traits\Helpers;

class FileUpload extends Component
{
    use Helpers;

    public function __construct(
        public object $field,
        public mixed $fieldValue = null,
        public ?bool $showFileUploadError = false,
        public ?string $showFileUploadErrorFor = null,
        public ?string $uploadFileError = null)
    {
        $this->field->key = data_get($field, 'key') ?: data_get($field, 'name');
        $this->field->tall_svg_upload = config('tall-forms.file-upload');
        $this->field->tall_svg_file = config('tall-forms.file-icon');
        $this->field->tall_svg_trash = config('tall-forms.trash-icon');
        $this->field->confirm_delete = data_get($field, 'confirm_delete', true);
        $this->field->confirm_msg = (string)trans(data_get($field, 'confirm_msg') ?: config('tall-forms.are-u-sure'));
        $this->uploadFileError = $this->uploadFileError ?: data_get($field, 'errorMsg') ?? (string)trans(config('tall-forms.upload-file-error'));
        $this->showFileUploadErrorFor = $this->showFileUploadErrorFor ?: $this->field->key;
    }

    public function class(): string
    {
        return "form-input tf-file-upload";
    }

    public function inputWrapper(): string
    {
        $class = "tf-file-upload-input-wrapper ";
        $class .= data_get($this->field, 'class');
        return Helpers::unique_words($class);
    }

    public function inputWrapperError(): string
    {
        return $this->inputWrapper() . " tf-field-error";
    }

    public function render(): View
    {
        return view('tall-forms::components.file-upload');
    }

    public function fileIcon($mime_type)
    {
        $icons = [
            'image' => 'file-image',
            'audio' => 'file-audio',
            'video' => 'file-video',
            'application/pdf' => 'file-pdf',
            'application/msword' => 'file-word',
            'application/vnd.ms-word' => 'file-word',
            'application/vnd.oasis.opendocument.text' => 'file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'file-word',
            'application/vnd.ms-excel' => 'file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'file-excel',
            'application/vnd.oasis.opendocument.spreadsheet' => 'file-excel',
            'application/vnd.ms-powerpoint' => 'file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml' => 'file-powerpoint',
            'application/vnd.oasis.opendocument.presentation' => 'file-powerpoint',
            'text/plain' => 'file-alt',
            'text/html' => 'file-code',
            'application/json' => 'file-code',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'file-word',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'file-excel',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'file-powerpoint',
            'application/gzip' => 'file-archive',
            'application/zip' => 'file-archive',
            'application/x-zip-compressed' => 'file-archive',
            'application/octet-stream' => 'file-archive',
        ];

        if (isset($icons[$mime_type])) return $icons[$mime_type];
        $mime_group = explode('/', $mime_type, 2)[0];

        return (isset($icons[$mime_group])) ? $icons[$mime_group] : 'file';
    }
}
