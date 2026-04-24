<?php

use CharlesLightjarvis\Todo\Enums\TodoPriorityEnum;
use CharlesLightjarvis\Todo\Enums\TodoStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->morphs('todoable'); // generates todoable_id and todoable_type
            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('status')->default(TodoStatusEnum::PENDING);
            $table->string('priority')->default(TodoPriorityEnum::LOW);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->nullableMorphs('creator');

            $table->index('status');
            $table->index('priority');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
