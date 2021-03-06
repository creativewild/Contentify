<?php namespace Contentify\Models;

/*
 * Single Table Inheritance Model.
 * It differs from common implementations: It does not have to use the class name 
 * but may use a custom value to link a PHP class to an database record.
 * The subclass models may set their $subclassId value to link themselves to a superclass
 * if they don't want to use their class names.
 */
abstract class StiModel extends BaseModel
{
    /**
     * The name of the field that stores the subclass
     * @var string
     */
    protected $subclassField = null;

    /**
     * Indicates if the inheriting class is a sublcass
     * @var boolean
     */
    protected $isSubclass = false;

    /**
     * ID value of the subclass.
     * If null, use the name of the class.
     * @var string|int|null
     */
    protected $subclassId = null;

    /**
     * Returns true if class is subclass
     * 
     * @return boolean
     */
    public function isSubclass()
    {
        return $this->isSubclass;
    }

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);

        if ($this->subclassField and $this->isSubclass()) {
            if ($this->subclassId) {
                $value = $this->subclassId;
            } else {
                $value = class_basename(get_class($this));
            }

            $this->setAttribute($this->subclassField, $value);
        }
    }

    /**
     * Create instance
     *
     * @return Model
     */
    public function mapData(array $attributes)
    {
        $className = get_class($this);

        return new $className;
    }

    public function newFromBuilder($attributes = array())
    {
        /*
         * Instead of using $this->newInstance(), call
         * newInstance() on the object from mapData
         */
        $instance = $this->mapData((array) $attributes)->newInstance(array(), true);
        $instance->setRawAttributes((array) $attributes, true);
        return $instance;
    }

    public function newQuery($excludeDeleted = true)
    {
        $builder = parent::newQuery($excludeDeleted);

        /*
         * If this is a subclass, add a condition to the query.
         * Use $subclassId as value or if it is null, the class name.
         */ 
        if ($this->subclassField and $this->isSubclass()) {
            if ($this->subclassId) {
                $value = $this->subclassId;
            } else {
                $value = class_basename(get_class($this));
            }

            $builder->where($this->subclassField, '=', $value);
        }

        return $builder;
    }

}