<?php

namespace kalanis\RedisWrapper\Shared;


/**
 * Class AWrapper
 * @package kalanis\RedisWrapper\Shared
 * Wrapper to plug Redis into PHP - abstract
 */
class AWrapper
{
    /** @var resource */
    public $context;

    /** @var Keys|null */
    protected $keyQuery = null;
    /** @var Data|null */
    protected $fileQuery = null;
    protected $showErrors = true;

    public function dir_closedir(): bool
    {
        try {
            return $this->keyQuery->close();
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    public function dir_opendir(string $path, int $options): bool
    {
        try {
            return $this->keyQuery->open($path, $options);
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    /**
     * @return string|false
     */
    public function dir_readdir()
    {
        try {
            return $this->keyQuery->read();
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    public function dir_rewinddir(): bool
    {
        try {
            return $this->keyQuery->rewind();
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    /**
     * @param string $path
     * @param int $mode
     * @param int $options
     * @return bool
     */
    public function mkdir(string $path, int $mode, int $options): bool
    {
        try {
            return $this->keyQuery->make($path, $mode, $options);
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    /**
     * @param string $path_from
     * @param string $path_to
     * @return bool
     */
    public function rename(string $path_from, string $path_to): bool
    {
        try {
            return $this->keyQuery->rename($path_from, $path_to);
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    /**
     * @param string $path
     * @param int $options
     * @return bool
     */
    public function rmdir(string $path, int $options): bool
    {
        try {
            return $this->keyQuery->remove($path, $options);
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    /**
     * @param int $cast_as
     * @return resource|bool
     */
    public function stream_cast(int $cast_as)
    {
        try {
            return $this->fileQuery->stream_cast($cast_as);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_close(): void
    {
        try {
            $this->fileQuery->stream_close();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
        }
    }

    public function stream_eof(): bool
    {
        try {
            return $this->fileQuery->stream_eof();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return true;
        }
    }

    public function stream_flush(): bool
    {
        try {
            return $this->fileQuery->stream_flush();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_lock(int $operation): bool
    {
        try {
            return $this->fileQuery->stream_lock($operation);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_metadata(string $path, int $option, $var): bool
    {
        try {
            return $this->fileQuery->stream_metadata($path, $option, $var);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_open(string $path, string $mode, int $options, string &$opened_path): bool
    {
        try {
            $this->canReport($options);
            return $this->fileQuery->stream_open($this->keyQuery, $path, $mode);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_read(int $count): string
    {
        try {
            return $this->fileQuery->stream_read($count);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_seek(int $offset, int $whence = SEEK_SET): bool
    {
        try {
            return $this->fileQuery->stream_seek($offset, $whence);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_set_option(int $option, int $arg1, int $arg2): bool
    {
        try {
            return $this->fileQuery->stream_set_option($option, $arg1, $arg2);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_stat(): array
    {
        try {
            return $this->fileQuery->stream_stat($this->keyQuery);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return [];
        }
    }

    public function stream_tell(): int
    {
        try {
            return $this->fileQuery->stream_tell();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return -1;
        }
    }

    public function stream_truncate(int $new_size): bool
    {
        try {
            return $this->fileQuery->stream_truncate($new_size);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_write(string $data): int
    {
        try {
            return $this->fileQuery->stream_write($data);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return 0;
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public function unlink(string $path): bool
    {
        try {
            return $this->fileQuery->unlink($path);
        } catch (RedisException $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
            return false;
        }
    }

    protected function canReport($opts): void
    {
        $this->showErrors = ($opts & STREAM_REPORT_ERRORS);
    }

    /**
     * @param RedisException $ex
     */
    protected function errorReport(RedisException $ex): void
    {
        if ($this->showErrors) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
        }
    }

    public function url_stat(string $path, int $flags): array
    {
        try {
            return $this->keyQuery->stats($path, $flags);
        } catch (RedisException $ex) {
            if ($flags & ~STREAM_URL_STAT_QUIET) {
                trigger_error($ex->getMessage(), E_USER_ERROR);
            }
            return [
                0 => 0,
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0,
                11 => -1,
                12 => -1,
            ];
        }
    }
}
