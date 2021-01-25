
    <?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateCamerasTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create("cameras", function (Blueprint $table) {

                $table->id();
                $table->biginteger('user_id')->unsigned();
                $table->biginteger('room_id')->unsigned();
                $table->string('name');
                $table->string('usetype');
                $table->timestamps();

                $table->foreign("user_id")->references("id")->on("users");
                $table->foreign("room_id")->references("id")->on("rooms");



                // ----------------------------------------------------
                // -- SELECT [cameras]--
                // ----------------------------------------------------
                // $query = DB::table("cameras")
                // ->leftJoin("users","users.id", "=", "cameras.user_id")
                // ->leftJoin("rooms","rooms.id", "=", "cameras.room_id")
                // ->get();
                // dd($query); //For checking



            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists("cameras");
        }
    }
