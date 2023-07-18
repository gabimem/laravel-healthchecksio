<?php


namespace Gabimem\LaravelHealthchecksIO;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Healthchecks
{
    public const COMMAND_SUCCESS = ''; // The 'success' command is the uri with no path added
    public const COMMAND_FAILURE = 'fail';
    public const COMMAND_START   = 'start';
    public const COMMAND_LOG     = 'log';

    /**
     * Send a ping by method post, with an optional description for the log
     * @param string $jobName
     * @param string $command
     * @param string|null $message
     */
    public static function ping(string $jobName, string $command, string $message = null): void
    {
        try {
            $url = self::getPingUrl($jobName, $command);

            $content = !empty($message) ? ['body' => $message] : [];
            Http::withHeaders(['Content-Type' => 'text/plain'])->send('POST', $url, $content);
        } catch (\Exception $e) {
            Log::channel(config('healthchecks.log'))->info('Healthchecks->ping exception: ', [$e->getMessage()]);
        }
    }

    /**
     * Generate the URI to ping
     * @throws \Exception
     */
    public static function getPingUrl(string $jobName, string $command = null): string
    {
        $jobs = config('healthchecks.jobs');
        $url  = config('healthchecks.url');
        if (!str_ends_with('/', $url))
            $url .= '/';

        //Check if there is a UUID for the job
        if (isset($jobs[$jobName]['uuid'])) {
            $url .= $jobs[$jobName]['uuid'];
        } else {
            //Check if there is a ping key for send requests in slug mode
            $key = config('healthchecks.key');
            if (!empty($key)) {
                //Check if there is a slug for the job
                if (isset($jobs[$jobName]['slug'])) {
                    $url .= $key . '/' . $jobs[$jobName]['slug'];
                } else {
                    $url .= $key . '/' . $jobName;
                }
            } else {
                throw new \Exception('A UUID or ping key is required to make requests to the server for job name: ' . $jobName);
            }
        }

        $url .= !empty($command) ? '/' . $command : '';
        return $url;
    }
}
