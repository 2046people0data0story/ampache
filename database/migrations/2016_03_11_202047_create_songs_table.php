<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->integer('id')
                  ->unsigned();
            $table->foreign('id')
                  ->references('id')
                  ->on('medias')
                  ->onDelete('cascade');
            $table->primary('id');
            $table->string('title');
            $table->mediumInteger('year');
            $table->mediumInteger('rate');
            $table->smallInteger('track')
                  ->nullable();
            $table->string('mbid', 36)
                  ->nullable();
            $table->string('composer')
                  ->nullable();

            $table->text('comment')
                  ->nullable();
            $table->text('lyrics')
                  ->nullable();
            $table->string('label')
                  ->nullable();
            $table->integer('catalog');
            $table->string('language')
                  ->nullable();
            $table->integer('license')
                  ->nullable();
            $table->binary('waveform')
                  ->nullable();
            $table->decimal('replaygain_track_gain', 10, 6)
                  ->nullable();
            $table->decimal('replaygain_track_peak', 10, 6)
                  ->nullable();
            $table->decimal('replaygain_album_gain', 10, 6)
                  ->nullable();
            $table->decimal('replaygain_album_peak', 10, 6)
                  ->nullable();
            $table->decimal('size')
                  ->unsigned()
                  ->nullable();
            $table->string('file', 4096)
                  ->nullable();
            $table->integer('album_id')
                  ->unsigned();
            $table->foreign('album_id')
                  ->references('id')
                  ->on('albums')
                  ->onDelete('cascade');
            $table->integer('artist_id')
                  ->unsigned();
            $table->foreign('artist_id')
                  ->references('id')
                  ->on('artists')
                  ->onDelete('cascade');
            $table->engine = 'MYISAM';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign('songs_id_foreign');
            $table->dropForeign('songs_album_id_foreign');
            $table->dropForeign('songs_artist_id_foreign');
        });
        Schema::drop('songs');
    }
}
