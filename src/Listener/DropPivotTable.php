<?php namespace Anomaly\MultipleFieldType\Listener;

use Anomaly\MultipleFieldType\MultipleFieldType;
use Anomaly\Streams\Platform\Assignment\Event\AssignmentWasDeleted;
use Illuminate\Database\Schema\Builder;

/**
 * Class DropPivotTable
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\MultipleFieldType\Listener
 */
class DropPivotTable
{

    /**
     * The schema builder.
     *
     * @var Builder
     */
    protected $schema;

    /**
     * Create a new StreamSchema instance.
     */
    public function __construct()
    {
        $this->schema = app('db')->connection()->getSchemaBuilder();
    }

    /**
     * Handle the event.
     *
     * @param AssignmentWasDeleted $event
     */
    public function handle(AssignmentWasDeleted $event)
    {
        $assignment = $event->getAssignment();

        $fieldType = $assignment->getFieldType();

        if (!$fieldType instanceof MultipleFieldType) {
            return;
        }

        $this->schema->dropIfExists(
            $table = $assignment->getStreamPrefix() . $assignment->getStreamSlug() . '_' . $fieldType->getField()
        );
    }
}
