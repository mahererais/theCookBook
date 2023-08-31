<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function add(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return 3 Recipes randomly find by Category id
     * and ordered by id ASC
     * */

    public function findRandomRecipesByCategory(Category $category, int $limit)
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->andWhere('r.category = :category')
            ->setParameter('category', $category)
            ->andWhere('r.user.status = :user_status') // Add this line to filter by user status
            ->setParameter('user_status', 'public') // Set the status to 'public'
            ->orderBy('r.id', 'ASC') // Change the order to 'ASC' to ensure cross-database compatibility
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
    * @return array[] Returns an array of recipe objects
    * @param string|null $string to find in recipes
    */

   public function searchRecipe(?string $query = null): ?array
   {
       return $this->createQueryBuilder('m')
            ->orderBy("m.title","ASC")
            ->where("m.title LIKE :query")
            ->setParameter("query", "%$query%")
            ->getQuery()
            ->getResult()
       ;
   }
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recipes find by one Category
     * 
     * */ 
    public function findByCategory(Category $category)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}