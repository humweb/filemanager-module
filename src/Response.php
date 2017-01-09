<?php

namespace Humweb\Filemanager;

/**
 * Response.
 */
class Response
{
    public function api($status, $payload = [])
    {
        return response()->json([
            'status' => $status,
            'data' => $payload,
        ]);
    }

    public function ok($payload)
    {
        return $this->api('ok', $payload);
    }

    public function error($message = '')
    {
        return $this->message('error', [
            'message' => $message,
        ]);
    }

    public function message($status, $message = '')
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function download($file, $disk)
    {
        $stream = $disk->readStream($file);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $disk->getMimetype($file),
            'Content-Length: ' => $disk->getSize($file),
            'Content-disposition' => 'attachment; filename="'.pathinfo($file, PATHINFO_BASENAME).'"',
        ]);
    }
}
