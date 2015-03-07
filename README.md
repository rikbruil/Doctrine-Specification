# Doctrine Specification
Doctrine [Specification pattern][specification_pattern] for building queries dynamically and with re-usable classes for composition.

## Usage

```php
<?php

// Not using the lib
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
<?php

// Using the lib
$spec = new AndX(
    new Equals('ended', 0),
    new OrX(
        new LowerThan('endDate', new \DateTime()),
        new AndX(
            new IsNull('endDate'),
            new LowerThan('startDate', new \DateTime('-4weeks'))
        )
    )
);

return $this->em->getRepository('Advertisement')->match($spec)->execute();
```

## Composition
A bonus of this pattern is composition, which makes specifications very reusable:

```php
<?php

use Entity\Advertisement;

class ExpiredAds extends Specification
{
    public function __construct()
    {
        $spec = new AndX(
                new Equals('ended', 0),
                new OrX(
                    new LowerThan('endDate', new \DateTime()),
                    new AndX(
                        new IsNull('endDate'),
                        new LowerThan('startDate', new \DateTime('-4weeks'))
                    )
                )
            );
        $this->setSpecification($spec);
    }
    
    public function supports($className)
    {
        return $className === Advertisement::class;
    }
}

use Entity\User;

class AdsByUser extends Specification
{
    public function __construct(User $user)
    {
        $this->setSpecification(
            new AndX(
                new Select('u'),
                new Join('user', 'u'),
                new Equals('id', $user->getId(), 'u')
            )
        );
    }

    public function supports($className)
    {
        return $className == Advertisement::class;
    }
}

class SomeService
{
    /**
     * Fetch Adverts that we should close but only for a specific company
     */
    public function myQuery(User $user)
    {
        $spec = new AndX(
            new ExpiredAds(),
            new AdsByUser($user)
        );

        return $this->em->getRepository('Advertisement')->match($spec)->execute();
    }
    
    /**
     * Fetch adverts paginated by Doctrine Paginator with joins intact.
     * A paginator can be iterated over like a normal array or Doctrine Collection
     */
    public function myPaginatedQuery(User $user, $page = 1, $size = 10)
    {
        $spec = new AndX(
            new ExpiredAds(),
            new AdsByUser($user)
        );
        
        $query = $this->em->getRepository('Advertisement')->match($spec);
        $query->setFirstResult(($page - 1) * $size))
            ->setMaxResults($size);
        return new Paginator($query);
    }
}
```

[specification_pattern]: http://en.wikipedia.org/wiki/Specification_pattern
