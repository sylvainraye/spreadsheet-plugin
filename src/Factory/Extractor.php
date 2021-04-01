<?php declare(strict_types=1);


namespace Kiboko\Plugin\Spreadsheet\Factory;

use Kiboko\Contract\Configurator\InvalidConfigurationException;
use Kiboko\Plugin\Spreadsheet;
use Kiboko\Contract\Configurator;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception as Symfony;
use Symfony\Component\Config\Definition\Processor;

final class Extractor implements Configurator\FactoryInterface
{
    private Processor $processor;
    private ConfigurationInterface $configuration;

    public function __construct()
    {
        $this->processor = new Processor();
        $this->configuration = new Spreadsheet\Configuration\Extractor();
    }

    public function configuration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @throws Configurator\ConfigurationExceptionInterface
     */
    public function normalize(array $config): array
    {
        try {
            return $this->processor->processConfiguration($this->configuration, $config);
        } catch (Symfony\InvalidTypeException | Symfony\InvalidConfigurationException $exception) {
            throw new Configurator\InvalidConfigurationException($exception->getMessage(), 0, $exception);
        }
    }

    public function validate(array $config): bool
    {
        try {
            if ($this->normalize($config)) {
                return true;
            }
        } catch (\Exception) {
        }

        return false;
    }

    public function compile(array $config): Repository\Extractor
    {
        if (array_key_exists('excel', $config)) {
            $builder = new Spreadsheet\Builder\Excel\Extractor(
                $config['file_path'],
                $config['excel']['sheet'],
                $config['excel']['skip_line'],
            );
        } elseif (array_key_exists('open_document', $config)) {
            $builder = new Spreadsheet\Builder\OpenDocument\Extractor(
                $config['file_path'],
                $config['open_document']['sheet'],
                $config['open_document']['skip_line'],
            );
        } elseif (array_key_exists('csv', $config)) {
            $builder = new Spreadsheet\Builder\CSV\Extractor(
                $config['file_path'],
                $config['csv']['skip_line'],
                $config['csv']['delimiter'],
                $config['csv']['enclosure'],
                $config['csv']['encoding'],
            );
        } else {
            throw new InvalidConfigurationException(
                'Could not determine if the factory should build an excel, an open_document or a csv extractor.'
            );
        }

        return new Repository\Extractor($builder);
    }
}
