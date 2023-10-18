<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metric_history_runs', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->float('accesibility_metric')->nullable();
            $table->float('pwa_metric')->nullable();
            $table->float('performance_metric')->nullable();
            $table->float('seo_metric')->nullable();
            $table->float('best_practices_metric')->nullable();
            $table->unsignedBigInteger('strategy_id');
            $table->timestamps();
    
            // Add a foreign key column for the strategy relationship
            
            $table->foreign('strategy_id')->references('id')->on('strategies');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metric_history_runs', function (Blueprint $table) {
            $table->dropForeign(['strategy_id']);
        });
    
        Schema::dropIfExists('metric_history_runs');
    }
    
};
