# PSR-3 logger implementation for Bitrix

В модуле есть 3 обертки над API Битрикс:

* FileLogger - является оберкткой AddMessage2Log, соотвественно путь сохранения логов определяется константой LOG_FILENAME
* JournalLogger - записывает логи в журнал событий /bitrix/admin/event_log.php?lang=ru, аргумент $context может содержать несколько важных параметов, соотвествующих полям таблицы
* DebugLogger - является отберткой над методами Bitrix\Main\Diag\Debug::dumpToFile и Bitrix\Main\Diag\Debug::writeToFile

Так же есть реализация с простой записью файла SimpleTextLogger - в конструкторе определяется, формат даты/времени и формат сообщения.

В модуле задана абстракция более высокого уровня LoggerManagerInterface, позволяет задать несколько логеров под разные уровни +
методы для логирования исключений и объекта с результатом Bitrix\Main\Result

### Пример использования:
```php
use Bx\Logger\LoggerManager;
use Bx\Logger\SimpleTextLogger;
use Bx\Logger\JournalLogger;
use Bx\Logger\FileLogger;
use Psr\Log\LogLevel;

$simpleTextLogger = new SimpleTextLogger(
    $_SERVER['DOCUMENT_ROOT'].'/log/ation_'.date('Y-m-d').'.log',   // путь сохранения лога
    'Y/m/d H:i:s',                                                  // формат даты/времени
    "{date} {level}:\t{message}"                                    // формат сообщения
)
$loggerManager = new LoggerManager($simpleTextLogger);              // создаем новый менеджер с логером по-умолчанию и типом default

$journalLogger = new JournalLogger('my.module');
$loggerManager->setLogger($journalLogger, LogLevel::ERROR);         // логи с ошибками будут записаны в журнал событий битрикса

$fileLogger = new FileLogger();
$loggerManager->setLogger($fileLogger, LogLevel::WARNING);          // логи с предупреждениями будут записаны в лог LOG_FILENAME

$loggerManager->info('Some info message');                          // сообщение будет записано через логгер SimpleTextLogger
$loggerManager->error('Some error message', [                       // сообщение будет записано в журнал событий
    'ITEM_ID' => 1,
]);
$loggerManager->warning('Some warning message');                    // сообщение будет записано в файл LOG_FILENAME

// создаем новый менеджер с логером по-умолчанию и типом test
$loggerManager = new LoggerManager(\Bx\Logger\TypedLoggerFactory::createTypedLogger($simpleTextLogger, 'test'));
```