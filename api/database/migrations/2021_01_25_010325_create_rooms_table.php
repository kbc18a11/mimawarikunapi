
    <?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateRoomsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create("rooms", function (Blueprint $table) {

                $table->id();
                $table->biginteger('user_id')->unsigned();
                $table->string('name');
                $table->string('class');
                $table->timestamps();


                //*********************************
                // Foreign KEY [ Uncomment if you want to use!! ]
                //*********************************
                $table->foreign("user_id")->references("id")->on("users");



                // ----------------------------------------------------
                // -- SELECT [rooms]--
                // ----------------------------------------------------
                // $query = DB::table("rooms")
                // ->leftJoin("users","users.id", "=", "rooms.id")
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
            Schema::dropIfExists("rooms");
        }
    }
