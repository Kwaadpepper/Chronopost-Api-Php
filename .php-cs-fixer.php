<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests'])
    ->name('*.php');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        // Base PSR-12 (inclut PSR-2 et PSR-1)
        '@PSR12' => true,

        // On ne ré-ordonne pas les imports (Slevomat gère les unused, l'ordre est libre)
        'ordered_imports' => false,

        // Syntaxe courte pour les tableaux
        'array_syntax' => ['syntax' => 'short'],

        // Virgule finale dans les déclarations multilignes (arrays, params, args)
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],

        // Guillemets simples par défaut
        'single_quote' => true,

        // PHP 8+ : ternaire nul → null coalescing  ($a ?: null → $a ?? null)
        'ternary_to_null_coalescing' => true,

        // PHP 8+ : ?Type quand la valeur par défaut est null  (Type $x = null → ?Type $x = null)
        'nullable_type_declaration_for_default_null_value' => true,

        // Modernisation des casts  (intval() → (int), strval() → (string), etc.)
        'modernize_types_casting' => true,

        // Propreté : else/return superflus
        'no_useless_else' => true,
        'no_useless_return' => true,

        // Nettoyage des commentaires et phpdoc vides
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,

        // Imports inutilisés (complémentaire à Slevomat/UnusedUses)
        'no_unused_imports' => true,
    ])
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setFinder($finder);
