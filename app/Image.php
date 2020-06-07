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

    public function dir(): string
    {
//        dump($this->getOwner()->imagesDir());
        return $this->getOwner()->imagesDir();
    }

    public function path(): string
    {
        return public_path($this->dir() . DIRECTORY_SEPARATOR . $this->name);
    }

    public function scopeOfClassroom(Builder $builder): Builder
    {
        return $builder->where('owner_type', Classroom::class);
    }

    /**
     * @param ImageOwnerContract $owner
     * @param string $base64
     * @param string $name
     * @return static
     */
    public static function store(ImageOwnerContract $owner, string $base64, string $name): self
    {
        $file = new static([
            'owner_type' => get_class($owner),
            'owner_id' => $owner->id,
            'name' => $name
        ]);

        abort_if(!$file->upload($base64), 400, "Failed to upload file");

        $file->save();

        return $file;
    }

    /**
     * @param string $base64
     * @param string $path
     * @return bool
     */
    public function upload(string $base64): bool
    {
        $this->createDir(
            $this->dir()
        );

//        dump($this->path());
        return (bool) file_put_contents($this->path(), base64_decode($base64));
    }

    public function createDir(string $dir): void
    {
        if (!is_dir($dir)) {
            $currentDir = null;

            foreach (explode(DIRECTORY_SEPARATOR, $dir) as $subDir) {
                $currentDir = $currentDir ? $currentDir . DIRECTORY_SEPARATOR . $subDir : $subDir;

                if (!is_dir($currentDir)) {
                    mkdir($currentDir);
                }
            }
        }
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
