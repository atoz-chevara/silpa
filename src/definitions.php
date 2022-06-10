<?php

namespace PHPMaker2021\silpa;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;

return [
    "cache" => function (ContainerInterface $c) {
        return new \Slim\HttpCache\CacheProvider();
    },
    "view" => function (ContainerInterface $c) {
        return new PhpRenderer("views/");
    },
    "flash" => function (ContainerInterface $c) {
        return new \Slim\Flash\Messages();
    },
    "audit" => function (ContainerInterface $c) {
        $logger = new Logger("audit"); // For audit trail
        $logger->pushHandler(new AuditTrailHandler("audit.log"));
        return $logger;
    },
    "log" => function (ContainerInterface $c) {
        global $RELATIVE_PATH;
        $logger = new Logger("log");
        $logger->pushHandler(new RotatingFileHandler($RELATIVE_PATH . "log.log"));
        return $logger;
    },
    "sqllogger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debugstack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "csrf" => function (ContainerInterface $c) {
        global $ResponseFactory;
        return new Guard($ResponseFactory, Config("CSRF_PREFIX"));
    },
    "debugstack" => \DI\create(DebugStack::class),
    "debugsqllogger" => \DI\create(DebugSqlLogger::class),
    "security" => \DI\create(AdvancedSecurity::class),
    "profile" => \DI\create(UserProfile::class),
    "language" => \DI\create(Language::class),
    "timer" => \DI\create(Timer::class),
    "session" => \DI\create(HttpSession::class),

    // Tables
    "levels" => \DI\create(Levels::class),
    "permissions2" => \DI\create(Permissions2::class),
    "users" => \DI\create(Users::class),
    "evaluators" => \DI\create(Evaluators::class),
    "satkers" => \DI\create(Satkers::class),
    "tahapan" => \DI\create(Tahapan::class),
    "wilayah" => \DI\create(Wilayah::class),
    "evaluasi" => \DI\create(Evaluasi::class),
    "apbk" => \DI\create(Apbk::class),
    "apbkp" => \DI\create(Apbkp::class),
    "pertanggungjawaban" => \DI\create(Pertanggungjawaban::class),
    "rapbk" => \DI\create(Rapbk::class),
    "tahun" => \DI\create(Tahun::class),
    "pertanggungjawaban2022" => \DI\create(Pertanggungjawaban2022::class),

    // User table
    "usertable" => \DI\get("users"),
];
