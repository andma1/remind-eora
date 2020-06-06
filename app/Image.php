<?php

namespace App;

use App\Contracts\ImageOwnerContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = [
        'owner_type',
        'owner_id',
        'name',
    ];

    public function path(): string
    {
        return public_path($this->getOwner()->dir() . '/' . $this->name);
    }

    public function scopeOfClassroom(Builder $builder): Builder
    {
        return $builder->where('owner_type', Classroom::class);
    }

    /**
     * @param int $docId
     * @param string $base64
     * @param string $name
     * @return static
     */
    public static function store(int $docId, string $base64, string $name): self
    {
        $file = new static([
            'doc_id' => $docId
        ]);

        abort_if(!$file->upload($base64, $name), 200, "Failed to upload file");

        $file->save();

        return $file;
    }

    /**
     * @param $base64
     * @param string $name
     * @return bool
     */
    public function upload(string $base64, string $name)
    {
//        $explode = explode(',', $base64);
//        $mimeType = str_replace(
//            [
//                'data:',
//                ';',
//                'base64',
//            ],
//            [
//                '', '', '',
//            ],
//            $explode[0]
//        );
//
//        $extension = Helper::guessExtension($mimeType);
//
//        if (null === $extension) {
//            return false;
//        }
//
//        $attachment = base64_decode($explode[1]);
//        $attachmentName = $name . '_' . uniqid() . '.' . $extension;
//
//        if (!is_dir(self::fullDirectory())) {
//            mkdir(self::fullDirectory());
//        }
//
//        $attachmentResult = (bool) file_put_contents(self::fullDirectory() . DIRECTORY_SEPARATOR . $attachmentName, $attachment);
//
//        if ($attachmentResult) {
//            $this->name = $attachmentName;
//        }
//
//        return $attachmentResult;
    }

    public function owner(): MorphTo
    {
        return $this->morphTo('owner');
    }

    public function getOwner(): ImageOwnerContract
    {
        return $this->owner;
    }
}
