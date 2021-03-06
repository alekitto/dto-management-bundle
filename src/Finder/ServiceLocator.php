<?php declare(strict_types=1);

namespace Fazland\DtoManagementBundle\Finder;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ServiceLocator implements ContainerInterface
{
    private $factories;

    /**
     * @param callable[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
        \ksort($this->factories);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return $id >= \array_keys($this->factories)[0];
    }

    /**
     * {@inheritdoc}
     */
    public function get($id): object
    {
        $id = (string) $id;
        $last = null;

        foreach ($this->factories as $version => $service) {
            if (\version_compare((string) $version, $id, '<=')) {
                $last = $version;
            } else {
                break;
            }
        }

        if (null === $last) {
            throw new ServiceNotFoundException($id, null, null, \array_keys($this->factories));
        }

        if (true === $factory = $this->factories[$last]) {
            throw new ServiceCircularReferenceException($last, [$last, $last]);
        }

        $this->factories[$last] = true;
        try {
            return $factory();
        } finally {
            $this->factories[$last] = $factory;
        }
    }

    public function __invoke($id): ?object
    {
        try {
            return $this->get($id);
        } catch (ServiceNotFoundException $e) {
            return null;
        }
    }
}
