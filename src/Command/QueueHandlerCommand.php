<?php

namespace App\Command;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class QueueHandlerCommand extends Command
{
    protected static $defaultName = 'queue:handle';
    protected $handlerPort;
    protected $consolePath;
    protected $processes = [];

    public function __construct($handlerPort, $consolePath, $name = null)
    {
        $this->handlerPort = $handlerPort;
        $this->consolePath = $consolePath;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Queue handle');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $childPid = pcntl_fork();
        if ($childPid) {
            return 0;
        }
        posix_setsid();
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);

        $this->startServer();

        return 0;
    }

    protected function startServer()
    {
        $path = $this->consolePath;
        $server = new HttpServer(function (ServerRequestInterface $request) use ($path) {
            $parameters = $request->getParsedBody();
//            $process = new Process([$path, $parameters['command']]);
//            $process->start();
//            exec("nohup {$path} {$parameters['command']} > /dev/null 2>&1 &");
            $response = json_encode($this->handle($request->getParsedBody()));
            return new Response(200, ['Content-Type' => 'application/json'], $response);
        });

        $loop = Factory::create();
        $socket = new SocketServer($this->handlerPort, $loop);
        $server->listen($socket);
        $loop->run();
    }

    protected function sortProcess()
    {
        foreach ($this->processes as $key => $process) {
            if (false === $process['process']->isRunning()) {
                unset($this->processes[$key]);
            }
        }

        sort($this->processes);
    }

    protected function handle(array $parameters)
    {
        $command = $parameters['command'];
        if ('stat' === $command) {
            $this->sortProcess();

            return ['ok' => true, 'list' => array_column($this->processes, 'command')];
        }

        $process = new Process([$this->consolePath, $parameters['command']]);
        $process->start();

        $command = "{$process->getPid()} {$parameters['command']}";
        $this->processes[] = ['command' => $command, 'process' => $process];

        return ['ok' => true];
    }
}