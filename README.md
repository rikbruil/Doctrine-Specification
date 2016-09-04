# Doctrine Specification 
[![Build Status](https://travis-ci.org/rikbruil/Doctrine-Specification.svg)](https://travis-ci.org/rikbruil/Doctrine-Specification)
[![Coverage Status](https://coveralls.io/repos/rikbruil/Doctrine-Specification/badge.svg?branch=master)](https://coveralls.io/r/rikbruil/Doctrine-Specification?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rikbruil/doctrine-specification/v/stable.svg)](https://packagist.org/packages/rikbruil/doctrine-specification)
[![License](https://poser.pugx.org/rikbruil/doctrine-specification/license.svg)](https://packagist.org/packages/rikbruil/doctrine-specification)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rikbruil/Doctrine-Specification/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rikbruil/Doctrine-Specification/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bb221821-1ba6-4613-b39f-f43718aaa92d/mini.png)](https://insight.sensiolabs.com/projects/bb221821-1ba6-4613-b39f-f43718aaa92d)

Doctrine [Specification pattern][specification_pattern] for building queries dynamically and with re-usable classes for composition.

This library started out as an adaptation of Benjamin Eberlei's [blog post][blog_post]. I was also inspired by the [Happyr Doctrine-Specification][happyr_spec] code, however this library has some small differences.
The main one is that SpecificationRepository->match() does not return the results directly, but returns the query object.

Since I like Doctrine's Paginator object, I wanted to be able to use that in combination with the Specification pattern.

## Usage

Install the latest version with `composer require rikbruil/doctrine-specification`

```php
// Not using the lib
// Note: Advertisement repository is an instance of the Doctrine default repository class
$qb = $this->em->getRepository('Advertisement')
    ->createQueryBuilder('r');

return $qb->where('r.ended = 0')
    ->andWhere(
        $qb->expr()->orX(
            'r.endDate < :now',
            $qb->expr()->andX(
                'r.endDate IS NULL',
                'r.startDate < :timeLimit'
            )
        )
    )
    ->setParameter('now', new \DateTime())
    ->setParameter('timeLimit', new \DateTime('-4weeks'))
    ->getQuery()
    ->getResult();
```

```php
use Rb\Specification\Doctrine\Condition\Equals;
use Rb\Specification\Doctrine\Condition\IsNull;
use Rb\Specification\Doctrine\Condition\LessThan;
use Rb\Specification\Doctrine\Logic\AndX;
use Rb\Specification\Doctrine\Logic\OrX;
use Rb\Specification\Doctrine\Specification;

// Using the lib
$spec = new Specification([
    new Equals('ended', 0),
    new OrX(
        new LessThan('endDate', new \DateTime()),
        new AndX(
            new IsNull('endDate'),
            new LessThan('startDate', new \DateTime('-4weeks'))
        )
    )
]);

// Note: Advertisement repository is an instance of the SpecificationRepository class
return $this->em->getRepository('Advertisement')->match($spec)->execute();
```

## Composition
A bonus of this pattern is composition, which makes specifications very reusable:

```php

use Entity\Advertisement;

class ExpiredAds extends Specification
{
    public function __construct()
    {
        $specs = [
            new Equals('ended', 0),
            new OrX(
                new LessThan('endDate', new \DateTime()),
                new AndX(
                    new IsNull('endDate'),
                    new LessThan('startDate', new \DateTime('-4weeks'))
                )
            )
        ];
        parent::__construct($specs);
    }
    
    public function isSatisfiedBy($value)
    {
        return $value === Advertisement::class;
    }
}

use Entity\User;

class AdsByUser extends Specification
{
    public function __construct(User $user)
    {
        $specs = [
            new Select('u'),
            new Join('user', 'u'),
            new Equals('id', $user->getId(), 'u'),
        ];
        
        parent::__construct($specs);
    }

    public function isSatisfiedBy($value)
    {
        return $value == Advertisement::class && parent::isSatisfiedBy($value);
    }
}

class SomeService
{
    /**
     * Fetch Adverts that we should close but only for a specific company
     */
    public function myQuery(User $user)
    {
        $spec = new Specification([
            new ExpiredAds(),
            new AdsByUser($user),
        ]);

        return $this->em->getRepository('Advertisement')->match($spec)->execute();
    }
    
    /**
     * Fetch adverts paginated by Doctrine Paginator with joins intact.
     * A paginator can be iterated over like a normal array or Doctrine Collection
     */
    public function myPaginatedQuery(User $user, $page = 1, $size = 10)
    {
        $spec = new Specification([
            new ExpiredAds(),
            new AdsByUser($user),
        ]);
        
        $query = $this->em->getRepository('Advertisement')->match($spec);
        $query->setFirstResult(($page - 1) * $size))
            ->setMaxResults($size);
        return new Paginator($query);
    }
}
```

## Requirements

Doctrine-Specification requires:

- PHP 5.5+
- Doctrine 2.2

However, I am planning to lower this to PHP 5.4+.

## License

Doctrine-Specification is licensed under the MIT License - see the `LICENSE` file for details

## Acknowledgements

This library is heavily inspired by Benjamin Eberlei's [blog post][blog_post]
and [Happyr's Doctrine-Specification library][happyr_spec].

[specification_pattern]: http://en.wikipedia.org/wiki/Specification_pattern
[happyr_spec]: https://github.com/Happyr/Doctrine-Specification
[blog_post]: http://www.whitewashing.de/2013/03/04/doctrine_repositories.html
