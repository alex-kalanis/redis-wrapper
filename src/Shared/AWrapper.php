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

    protected ?Keys $keyQuery;
    protected ?Data $fileQuery;
    protected bool $showErrors = true;

    public function dir_closedir(): bool
    {
        try {
            return $this->getKeyQuery()->close();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function dir_opendir(string $path, int $options): bool
    {
        try {
            return $this->getKeyQuery()->open($path, $options);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    /**
     * @return string|false
     */
    public function dir_readdir()
    {
        try {
            return $this->getKeyQuery()->read();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function dir_rewinddir(): bool
    {
        try {
            return $this->getKeyQuery()->rewind();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
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
            return $this->getKeyQuery()->make($path, $mode, $options);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
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
            return $this->getKeyQuery()->rename($path_from, $path_to);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
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
            return $this->getKeyQuery()->remove($path, $options);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
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
            return $this->getFileQuery()->stream_cast($cast_as);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_close(): void
    {
        try {
            $this->getFileQuery()->stream_close();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
        }
    }

    public function stream_eof(): bool
    {
        try {
            return $this->getFileQuery()->stream_eof();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return true;
        }
    }

    public function stream_flush(): bool
    {
        try {
            return $this->getFileQuery()->stream_flush();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_lock(int $operation): bool
    {
        try {
            return $this->getFileQuery()->stream_lock($operation);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    /**
     * @param string $path
     * @param int $option
     * @param mixed $var
     * @return bool
     */
    public function stream_metadata(string $path, int $option, $var): bool
    {
        try {
            return $this->getFileQuery()->stream_metadata($path, $option, $var);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_open(string $path, string $mode, int $options, string &$opened_path): bool
    {
        try {
            $this->canReport($options);
            return $this->getFileQuery()->stream_open($this->getKeyQuery(), $path, $mode);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    /**
     * @param int $count
     * @return bool|string
     */
    public function stream_read(int $count)
    {
        try {
            return $this->getFileQuery()->stream_read($count);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_seek(int $offset, int $whence = SEEK_SET): bool
    {
        try {
            return $this->getFileQuery()->stream_seek($offset, $whence);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_set_option(int $option, int $arg1, int $arg2): bool
    {
        try {
            return $this->getFileQuery()->stream_set_option($option, $arg1, $arg2);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    /**
     * @return array<int, string|int>
     */
    public function stream_stat(): array
    {
        try {
            return $this->getFileQuery()->stream_stat($this->getKeyQuery());
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return [];
        }
    }

    public function stream_tell(): int
    {
        try {
            return $this->getFileQuery()->stream_tell();
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return -1;
        }
    }

    public function stream_truncate(int $new_size): bool
    {
        try {
            return $this->getFileQuery()->stream_truncate($new_size);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    public function stream_write(string $data): int
    {
        try {
            return $this->getFileQuery()->stream_write($data);
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
            return $this->getFileQuery()->unlink($path);
        } catch (RedisException $ex) {
            $this->errorReport($ex);
            return false;
        }
    }

    /**
     * @param int $opts
     */
    protected function canReport($opts): void
    {
        $this->showErrors = boolval($opts & STREAM_REPORT_ERRORS);
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

    /**
     * @param string $path
     * @param int $flags
     * @return array<int, string|int>
     */
    public function url_stat(string $path, int $flags): array
    {
        try {
            return $this->getKeyQuery()->stats($path, $flags);
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

    /**
     * @throws RedisException
     * @return Keys
     */
    protected function getKeyQuery(): Keys
    {
        if (empty($this->keyQuery)) {
            throw new RedisException('Set Key query variable first!');
        }
        return $this->keyQuery;
    }

    /**
     * @throws RedisException
     * @return Data
     */
    protected function getFileQuery(): Data
    {
        if (empty($this->fileQuery)) {
            throw new RedisException('Set File query variable first!');
        }
        return $this->fileQuery;
    }
}
