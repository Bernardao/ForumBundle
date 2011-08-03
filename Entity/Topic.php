<?php
/**
 * Epixa - ForumBundle
 */

namespace Epixa\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    DateTime,
    InvalidArgumentException;

/**
 * A representation of a forum topic
 * 
 * @category   EpixaForumBundle
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="epixa_forum_topic")
 */
class Topic
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string")
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="topics")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Category
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="topic", fetch="EXTRA_LAZY")
     * @var Post[]
     */
    protected $posts;


    /**
     * Initializes a new Topic
     *
     * The creation date is set to now and the posts collection is initialized.
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();
        $this->posts = new ArrayCollection();
    }
    
    /**
     * Gets the unique identifier of this entity
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the topic title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the topic title
     *
     * @param string $title
     * @return Topic *Fluent interface*
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
        return $this;
    }

    /**
     * Gets the date that this entity was created
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date this entity was created
     *
     * @throws \InvalidArgumentException
     * @param \DateTime|string|integer $date
     * @return Topic *Fluent interface*
     */
    public function setDateCreated($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        } else if (is_int($date)) {
            $date = new DateTime(sprintf('@%d', $date));
        } else if (!$date instanceof DateTime) {
            throw new InvalidArgumentException(sprintf(
                'Expecting string, integer or DateTime, but got `%s`',
                is_object($date) ? get_class($date) : gettype($date)
            ));
        }

        $this->dateCreated = $date;
        return $this;
    }

    /**
     * Gets the category of this topic
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category of this topic
     *
     * @param Category $category
     * @return Topic *Fluent interface*
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Gets a collection of posts for this topic
     *
     * @param integer $page  What page of results to return
     * @param integer $total The total maximum results to return
     * @return \Doctrine\Common\Collections\ArrayCollection|Post[]
     */
    public function getPosts($page, $total = 50)
    {
        $offset = ($page - 1) * $total;
        return $this->posts->slice($offset, $total);
    }
}