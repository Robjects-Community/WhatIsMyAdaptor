<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Authentication\Authenticator;

use Authentication\AbstractCollection;
use Authentication\Identifier\IdentifierCollection;
use Cake\Core\App;
use RuntimeException;
use function Cake\Core\deprecationWarning;

/**
 * @extends \Authentication\AbstractCollection<\Authentication\Authenticator\AuthenticatorInterface>
 */
class AuthenticatorCollection extends AbstractCollection
{
    /**
     * Identifier collection.
     *
     * @var \Authentication\Identifier\IdentifierCollection
     */
    protected IdentifierCollection $_identifiers;

    /**
     * Constructor.
     *
     * @param \Authentication\Identifier\IdentifierCollection $identifiers Identifiers collection.
     * @param array<string, mixed> $config Config array.
     */
    public function __construct(IdentifierCollection $identifiers, array $config = [])
    {
        $this->_identifiers = $identifiers;
        if ($identifiers->count() > 0) {
            deprecationWarning(
                '3.3.0',
                'loadIdentifier() usage is deprecated. Directly pass `\'identifier\'` config to the Authenticator.',
            );
        }

        parent::__construct($config);
    }

    /**
     * Creates authenticator instance.
     *
     * @param \Authentication\Authenticator\AuthenticatorInterface|class-string<\Authentication\Authenticator\AuthenticatorInterface> $class Authenticator class.
     * @param string $alias Authenticator alias.
     * @param array<string, mixed> $config Config array.
     * @return \Authentication\Authenticator\AuthenticatorInterface
     * @throws \RuntimeException
     */
    protected function _create(object|string $class, string $alias, array $config): AuthenticatorInterface
    {
        if (is_string($class)) {
            if (!empty($config['identifier'])) {
                $this->_identifiers = new IdentifierCollection((array)$config['identifier']);
            }

            return new $class($this->_identifiers, $config);
        }

        return $class;
    }

    /**
     * Resolves authenticator class name.
     *
     * @param string $class Class name to be resolved.
     * @return class-string<\Authentication\Authenticator\AuthenticatorInterface>|null
     */
    protected function _resolveClassName(string $class): ?string
    {
        /** @var class-string<\Authentication\Authenticator\AuthenticatorInterface>|null */
        return App::className($class, 'Authenticator', 'Authenticator');
    }

    /**
     * @param string $class Missing class.
     * @param string|null $plugin Class plugin.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        if ($plugin) {
            $class = $plugin . '.' . $class;
        }

        $message = sprintf('Authenticator class `%s` was not found.', $class);

        throw new RuntimeException($message);
    }
}
