<?php

namespace kalanis\RedisWrapper\Shared;


/**
 * Class Data
 * @package kalanis\RedisWrapper\Shared
 * Wrapper to plug Redis info into PHP - files part
 */
abstract class Data extends AOperations
{
    protected string $path = '';
    protected int $size = 0;
    protected int $position = 0;
    protected bool $writeMode = false; // read - false, write - true

    /**
     * @param int $cast_as
     * @return resource|bool
     */
    public function stream_cast(int $cast_as)
    {
        return false;
    }

    public function stream_close(): void
    {
    }

    public function stream_eof(): bool
    {
        return (!$this->writeMode) && ($this->position >= $this->size);
    }

    public function stream_flush(): bool
    {
        return false;
    }

    public function stream_lock(int $operation): bool
    {
        return false;
    }

    /**
     * @param string $path
     * @param int $option
     * @param mixed $var
     * @return bool
     */
    public function stream_metadata(string $path, int $option, $var): bool
    {
        return false;
    }

    /**
     * @param Keys $libDir
     * @param string $path
     * @param string $mode
     * @throws RedisException
     * @return bool
     */
    public function stream_open(Keys $libDir, string $path, string $mode): bool
    {
        $this->path = $path;
        $this->writeMode = $this->parseWriteMode($mode);

        if (!$this->writeMode) {
            $stat = $this->stream_stat($libDir);
            $this->size = intval($stat[7]); // stats - max available size
        }
        $this->position = 0;
        return true;
    }

    /**
     * @param string $mode
     * @throws RedisException
     * @return bool
     */
    protected function parseWriteMode(string $mode): bool
    {
        $mod = strtolower(substr(strtr($mode, ['+' => '', 'b' => '', 'e' => '']), 0, 1));
        if ('r' == $mod) {
            return false;
        }
        if (in_array($mod, ['w', 'a', 'x', 'c'])) {
            return true;
        }
        throw new RedisException('Got problematic mode: ' . $mode);
    }

    /**
     * @param int $count
     * @return string
     */
    public function stream_read(int $count): string
    {
        $data = strval($this->get($this->parsePath($this->path)));
        $sub = empty($count) ? substr($data, $this->position) : substr($data, $this->position, $count);
        $this->position += strlen($sub);
        return $sub;
    }

    public function stream_seek(int $offset, int $whence = SEEK_SET): bool
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < $this->size && 0 <= $offset) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }

            case SEEK_CUR:
                if (0 <= $offset) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }

            case SEEK_END:
                if ($this->size + 0 <= $offset) {
                    $this->position = $this->size + $offset;
                    return true;
                } else {
                    return false;
                }

            default:
                return false;
        }
    }

    public function stream_set_option(int $option, int $arg1, int $arg2): bool
    {
        return false;
    }

    /**
     * @param Keys $libDir
     * @throws RedisException
     * @return array<int, string|int>
     */
    public function stream_stat(Keys $libDir): array
    {
        return $libDir->stats($this->path, 0);
    }

    public function stream_tell(): int
    {
        return $this->position;
    }

    public function stream_truncate(int $new_size): bool
    {
        return false;
    }

    /**
     * @param string $data
     * @throws RedisException
     * @return int
     */
    public function stream_write(string $data): int
    {
        if (!$this->writeMode) {
            throw new RedisException('File not open for writing!');
        }
        $currentInData = strval($this->get($this->parsePath($this->path)));
        $currentDataLen = strlen($currentInData);
        $dataLen = strlen($data);
        if ($currentDataLen != $this->position) {
            throw new RedisException(sprintf('Bad write seek. Want %d got %d ', $this->position, $currentDataLen));
        }
        $this->position = $this->append($this->parsePath($this->path), $data);
        return $dataLen;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function unlink(string $path): bool
    {
        return boolval($this->del($this->parsePath($path)));
    }

    /**
     * Get content in key; empty if non-existent
     * @param string $key
     * @return string|null
     */
    abstract protected function get(string $key): ?string;

    /**
     * Append content into key
     * @param string $key
     * @param string $value
     * @return int
     */
    abstract protected function append(string $key, string $value): int;

    /**
     * Delete key
     * @param string $key
     * @return bool
     */
    abstract protected function del(string $key): bool;
}
