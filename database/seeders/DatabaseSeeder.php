<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\City;
use App\Models\FavouriteCar;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model as CarModel;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        CarType::factory()
             ->sequence(
                 ['name' => 'Sedan'],
                 ['name' => 'Hatchback'],
                 ['name' => 'SUV'],
                 ['name' => 'Pickup Truck'],
                 ['name' => 'Minivan'],
                 ['name' => 'Jeep'],
                 ['name' => 'Coupe'],
                 ['name' => 'Crossover'],
                 ['name' => 'Sports Car'],
             )
             ->count(9)
             ->create();

        FuelType::factory()
            ->sequence(
                ['name' => 'Gasoline'],
                ['name' => 'Diesel'],
                ['name' => 'Electric'],
                ['name' => 'Hybrid'],
            )
            ->count(4)
            ->create();

        $states = [

            'Abia' => [
                'Aba North', 'Aba South', 'Arochukwu', 'Bende', 'Ikwuano', 'Isiala Ngwa North',
                'Isiala Ngwa South', 'Isuikwuato', 'Obi Ngwa', 'Ohafia', 'Osisioma', 'Ugwunagbo',
                'Ukwa East', 'Ukwa West', 'Umu Nneochi', 'Umuahia North', 'Umuahia South',
            ],

            'Adamawa' => [
                'Demsa', 'Fufore', 'Ganye', 'Girei', 'Gombi', 'Guyuk', 'Hong',
                'Jada', 'Lamurde', 'Madagali', 'Maiha', 'Mayo Belwa', 'Michika',
                'Mubi North', 'Mubi South', 'Numan', 'Shelleng', 'Song', 'Toungo',
                'Yola North', 'Yola South',
            ],

            'Akwa Ibom' => [
                'Abak', 'Eastern Obolo', 'Eket', 'Esit Eket', 'Essien Udim', 'Etim Ekpo',
                'Etinan', 'Ibeno', 'Ibesikpo Asutan', 'Ibiono Ibom', 'Ika', 'Ikono',
                'Ikot Abasi', 'Ikot Ekpene', 'Ini', 'Itu', 'Mbo', 'Mkpat Enin', 'Nsit Atai',
                'Nsit Ibom', 'Nsit Ubium', 'Obot Akara', 'Okobo', 'Onna', 'Oron', 'Oruk Anam',
                'Udung Uko', 'Ukanafun', 'Uruan', 'Urue-Offong/Oruko', 'Uyo',
            ],

            'Anambra' => [
                'Aguata', 'Anambra East', 'Anambra West', 'Anaocha', 'Awka North',
                'Awka South', 'Ayamelum', 'Dunukofia', 'Ekwusigo', 'Idemili North',
                'Idemili South', 'Ihiala', 'Njikoka', 'Nnewi North', 'Nnewi South',
                'Ogbaru', 'Onitsha North', 'Onitsha South', 'Orumba North', 'Orumba South', 'Oyi',
            ],

            'Bauchi' => [
                'Alkaleri', 'Bauchi', 'Bogoro', 'Damban', 'Darazo', 'Dass', 'Gamawa',
                'Ganjuwa', 'Giade', 'Itas/Gadau', "Jama'are", 'Katagum', 'Kirfi',
                'Misau', 'Ningi', 'Shira', 'Tafawa Balewa', 'Toro', 'Warji', 'Zaki',
            ],

            'Bayelsa' => [
                'Brass', 'Ekeremor', 'Kolokuma/Opokuma', 'Nembe', 'Ogbia',
                'Sagbama', 'Southern Ijaw', 'Yenagoa',
            ],

            'Benue' => [
                'Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West',
                'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi',
                'Obi', 'Ogbadibo', 'Ohimini', 'Oju', 'Okpokwu', 'Oturkpo',
                'Tarka', 'Ukum', 'Ushongo', 'Vandeikya',
            ],

            'Borno' => [
                'Abadam', 'Askira/Uba', 'Bama', 'Bayo', 'Biu', 'Chibok', 'Damboa',
                'Dikwa', 'Gubio', 'Guzamala', 'Gwoza', 'Hawul', 'Jere', 'Kaga',
                'Kala/Balge', 'Konduga', 'Kukawa', 'Kwaya Kusar', 'Mafa', 'Magumeri',
                'Maiduguri', 'Marte', 'Mobbar', 'Monguno', 'Ngala', 'Nganzai', 'Shani',
            ],

            'Cross River' => [
                'Abi', 'Akamkpa', 'Akpabuyo', 'Bakassi', 'Bekwarra', 'Biase', 'Boki',
                'Calabar Municipal', 'Calabar South', 'Etung', 'Ikom', 'Obanliku',
                'Obubra', 'Obudu', 'Odukpani', 'Ogoja', 'Yakuur', 'Yala',
            ],

            'Delta' => [
                'Aniocha North', 'Aniocha South', 'Bomadi', 'Burutu', 'Ethiope East',
                'Ethiope West', 'Ika North East', 'Ika South', 'Isoko North', 'Isoko South',
                'Ndokwa East', 'Ndokwa West', 'Okpe', 'Oshimili North', 'Oshimili South',
                'Patani', 'Sapele', 'Udu', 'Ughelli North', 'Ughelli South',
                'Ukwuani', 'Uvwie', 'Warri North', 'Warri South', 'Warri South West',
            ],

            'Ebonyi' => [
                'Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 'Ezza North',
                'Ezza South', 'Ikwo', 'Ishielu', 'Ivo', 'Izzi', 'Ohaozara', 'Ohaukwu', 'Onicha',
            ],

            'Edo' => [
                'Akoko-Edo', 'Egor', 'Esan Central', 'Esan North-East', 'Esan South-East',
                'Esan West', 'Etsako Central', 'Etsako East', 'Etsako West',
                'Igueben', 'Ikpoba-Okha', 'Orhionmwon', 'Oredo', 'Ovia North-East',
                'Ovia South-West', 'Owan East', 'Owan West', 'Uhunmwonde',
            ],

            'Ekiti' => [
                'Ado Ekiti', 'Efon', 'Ekiti East', 'Ekiti South-West', 'Ekiti West',
                'Emure', 'Gbonyin', 'Ido Osi', 'Ijero', 'Ikere', 'Ikole',
                'Ilejemeje', 'Irepodun/Ifelodun', 'Ise/Orun', 'Moba', 'Oye',
            ],

            'Enugu' => [
                'Aninri', 'Awgu', 'Enugu East', 'Enugu North', 'Enugu South',
                'Ezeagu', 'Igbo Etiti', 'Igbo Eze North', 'Igbo Eze South',
                'Isi Uzo', 'Nkanu East', 'Nkanu West', 'Nsukka', 'Oji River',
                'Udenu', 'Udi', 'Uzo Uwani',
            ],

            'FCT' => [
                'Abaji', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 'Municipal Area Council',
            ],

            'Gombe' => [
                'Akko', 'Balanga', 'Billiri', 'Dukku', 'Funakaye', 'Gombe',
                'Kaltungo', 'Kwami', 'Nafada', 'Shongom', 'Yamaltu/Deba',
            ],

            'Imo' => [
                'Aboh Mbaise', 'Ahiazu Mbaise', 'Ehime Mbano', 'Ezinihitte',
                'Ideato North', 'Ideato South', 'Ihitte/Uboma', 'Ikeduru',
                'Isiala Mbano', 'Isu', 'Mbaitoli', 'Ngor Okpala', 'Njaba',
                'Nkwerre', 'Nwangele', 'Obowo', 'Oguta', 'Ohaji/Egbema',
                'Okigwe', 'Onuimo', 'Orlu', 'Orsu', 'Oru East', 'Oru West',
                'Owerri Municipal', 'Owerri North', 'Owerri West',
            ],

            'Jigawa' => [
                'Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 'Dutse', 'Gagarawa',
                'Garki', 'Gumel', 'Guri', 'Gwaram', 'Gwiwa', 'Hadejia', 'Jahun', 'Kafin Hausa',
                'Kaugama', 'Kazaure', 'Kiri Kasama', 'Kiyawa', 'Maigatari', 'Malam Madori',
                'Miga', 'Ringim', 'Roni', 'Sule Tankarkar', 'Taura', 'Yankwashi',
            ],

            'Kaduna' => [
                'Birnin Gwari', 'Chikun', 'Giwa', 'Igabi', 'Ikara', 'Jaba', "Jema'a",
                'Kachia', 'Kaduna North', 'Kaduna South', 'Kagarko', 'Kajuru', 'Kaura',
                'Kauru', 'Kubau', 'Kudan', 'Lere', 'Makarfi', 'Sabon Gari', 'Sanga',
                'Soba', 'Zangon Kataf', 'Zaria',
            ],

            'Kano' => [
                'Ajingi', 'Albasu', 'Bagwai', 'Bebeji', 'Bichi', 'Bunkure', 'Dala', 'Dambatta',
                'Dawakin Kudu', 'Dawakin Tofa', 'Doguwa', 'Fagge', 'Gabasawa', 'Garko',
                'Garun Mallam', 'Gaya', 'Gezawa', 'Gwale', 'Gwarzo', 'Kabo', 'Kano Municipal',
                'Karaye', 'Kibiya', 'Kiru', 'Kumbotso', 'Kunchi', 'Kura', 'Madobi',
                'Makoda', 'Minjibir', 'Nasarawa', 'Rano', 'Rimin Gado', 'Rogo', 'Shanono',
                'Sumaila', 'Takai', 'Tarauni', 'Tofa', 'Tsanyawa', 'Tudun Wada', 'Ungogo', 'Warawa', 'Wudil',
            ],

            'Katsina' => [
                'Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dandume',
                'Danja', 'Dan Musa', 'Daura', 'Dutsi', 'Dutsin-Ma', 'Faskari', 'Funtua',
                'Ingawa', 'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina',
                'Kurfi', 'Kusada', "Mai'Adua", 'Malumfashi', 'Mani', 'Mashi', 'Matazu',
                'Musawa', 'Rimi', 'Sabuwa', 'Safana', 'Sandamu', 'Zango',
            ],

            'Kebbi' => [
                'Aleiro', 'Arewa Dandi', 'Argungu', 'Augie', 'Bagudo', 'Birnin Kebbi',
                'Bunza', 'Dandi', 'Fakai', 'Gwandu', 'Jega', 'Kalgo', 'Koko/Besse',
                'Maiyama', 'Ngaski', 'Sakaba', 'Shanga', 'Suru', 'Wasagu/Danko', 'Yauri', 'Zuru',
            ],

            'Kogi' => [
                'Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 'Ibaji', 'Idah',
                'Igalamela-Odolu', 'Ijumu', 'Kabba/Bunu', 'Kogi', 'Lokoja', 'Mopa-Muro',
                'Ofu', 'Ogori/Magongo', 'Okehi', 'Okene', 'Olamaboro', 'Omala',
                'Yagba East', 'Yagba West',
            ],

            'Kwara' => [
                'Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 'Ilorin East', 'Ilorin South',
                'Ilorin West', 'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 'Oke Ero', 'Oyun', 'Pategi',
            ],

            'Lagos' => [
                'Agege', 'Ajeromi-Ifelodun', 'Alimosho', 'Amuwo-Odofin', 'Apapa', 'Badagry',
                'Epe', 'Eti-Osa', 'Ibeju-Lekki', 'Ifako-Ijaiye', 'Ikeja', 'Ikorodu',
                'Kosofe', 'Lagos Island', 'Lagos Mainland', 'Mushin', 'Ojo',
                'Oshodi-Isolo', 'Shomolu', 'Surulere',
            ],

            'Nasarawa' => [
                'Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 'Keffi', 'Kokona', 'Lafia',
                'Nasarawa', 'Nasarawa Egon', 'Obi', 'Toto', 'Wamba',
            ],

            'Niger' => [
                'Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 'Chanchaga', 'Edati', 'Gbako',
                'Gurara', 'Katcha', 'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga',
                'Mashegu', 'Mokwa', 'Munya', 'Paiko', 'Rafi', 'Rijau', 'Shiroro', 'Suleja',
                'Tafa', 'Wushishi',
            ],

            'Ogun' => [
                'Abeokuta North', 'Abeokuta South', 'Ado-Odo/Ota', 'Egbado North',
                'Egbado South', 'Ewekoro', 'Ifo', 'Ijebu East', 'Ijebu North', 'Ijebu North East',
                'Ijebu Ode', 'Ikenne', 'Imeko Afon', 'Ipokia', 'Obafemi Owode',
                'Odeda', 'Odogbolu', 'Ogun Waterside', 'Remo North', 'Shagamu',
            ],

            'Ondo' => [
                'Akoko North-East', 'Akoko North-West', 'Akoko South-West',
                'Akoko South-East', 'Akure North', 'Akure South', 'Ese Odo', 'Idanre',
                'Ifedore', 'Ilaje', 'Ile Oluji/Okeigbo', 'Irele', 'Odigbo',
                'Okitipupa', 'Ondo East', 'Ondo West', 'Ose', 'Owo',
            ],

            'Osun' => [
                'Aiyedaade', 'Aiyedire', 'Atakunmosa East', 'Atakunmosa West',
                'Boluwaduro', 'Boripe', 'Ede North', 'Ede South', 'Egbedore',
                'Ejigbo', 'Ife Central', 'Ife East', 'Ife North', 'Ife South',
                'Ifedayo', 'Ifelodun', 'Ila', 'Ilesa East', 'Ilesa West', 'Irepodun',
                'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo Otin', 'Ola Oluwa', 'Olorunda',
                'Oriade', 'Orolu', 'Osogbo',
            ],

            'Oyo' => [
                'Afijio', 'Akinyele', 'Atiba', 'Atisbo', 'Egbeda', 'Ibadan North', 'Ibadan North-East',
                'Ibadan North-West', 'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central',
                'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 'Iseyin', 'Itesiwaju',
                'Iwajowa', 'Kajola', 'Lagelu', 'Ogbomosho North', 'Ogbomosho South',
                'Ogo Oluwa', 'Olorunsogo', 'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire',
                'Oyo East', 'Oyo West', 'Saki East', 'Saki West', 'Surulere',
            ],

            'Plateau' => [
                'Barkin Ladi', 'Bassa', 'Bokkos', 'Jos East', 'Jos North', 'Jos South',
                'Kanam', 'Kanke', 'Langtang North', 'Langtang South', 'Mangu',
                'Mikang', 'Pankshin', "Qua'an Pan", 'Riyom', 'Shendam', 'Wase',
            ],

            'Rivers' => [
                'Abua/Odual', 'Ahoada East', 'Ahoada West', 'Akuku-Toru',
                'Andoni', 'Asari-Toru', 'Bonny', 'Degema', 'Eleme', 'Emohua', 'Etche',
                'Gokana', 'Ikwerre', 'Khana', 'Obio/Akpor', 'Ogba/Egbema/Ndoni',
                'Ogu/Bolo', 'Okrika', 'Omuma', 'Opobo/Nkoro', 'Oyigbo', 'Port Harcourt',
                'Tai',
            ],

            'Sokoto' => [
                'Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 'Gudu',
                'Gwadabawa', 'Illela', 'Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni',
                'Shagari', 'Silame', 'Sokoto North', 'Sokoto South', 'Tambuwal',
                'Tangaza', 'Tureta', 'Wamako', 'Wurno', 'Yabo',
            ],

            'Taraba' => [
                'Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo',
                'Karim Lamido', 'Kumi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing',
            ],

            'Yobe' => [
                'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba',
                'Gulani', 'Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru',
                'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari',
            ],

            'Zamfara' => [
                'Anka', 'Bakura', 'Birnin Magaji/Kiyaw', 'Bukkuyum', 'Bungudu',
                'Gummi', 'Gusau', 'Kaura Namoda', 'Maradun', 'Maru', 'Shinkafi',
                'Talata Mafara', 'Chafe', 'Zurmi',
            ],

        ];

        foreach ($states as $state => $cities) {
            State::factory()
                ->state(['name' => $state])
                ->has(
                    City::factory()
                    ->count(count($cities))
                    ->sequence(...array_map(fn ($city) => ['name' => $city], $cities))

                )
                ->create();
        }

        $makers = [

            'Toyota' => [
                'Camry', 'Corolla', 'Rav4', 'Highlander', 'Land Cruiser', 'Prado',
                'Hilux', 'Yaris', 'Avalon', 'Fortuner', 'Tacoma', 'Tundra', 'Sienna',
            ],

            'Honda' => [
                'Civic', 'Accord', 'CR-V', 'Pilot', 'Fit', 'Odyssey', 'HR-V',
                'Ridgeline', 'Insight', 'Passport',
            ],

            'Mercedes-Benz' => [
                'C-Class', 'E-Class', 'S-Class', 'A-Class', 'GLA', 'GLC', 'GLE',
                'GLS', 'CLA', 'G-Class', 'Viano', 'Sprinter',
            ],

            'BMW' => [
                '1 Series', '2 Series', '3 Series', '4 Series', '5 Series', '6 Series',
                '7 Series', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'i3', 'i8',
            ],

            'Audi' => [
                'A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'TT',
            ],

            'Volkswagen' => [
                'Golf', 'Passat', 'Jetta', 'Polo', 'Tiguan', 'Touareg', 'Arteon',
                'Beetle', 'Atlas',
            ],

            'Hyundai' => [
                'Elantra', 'Sonata', 'Accent', 'Santa Fe', 'Tucson', 'Venue', 'Palisade', 'Kona',
            ],

            'Kia' => [
                'Rio', 'Cerato', 'Optima', 'Sportage', 'Sorento', 'Seltos',
                'Telluride', 'Picanto', 'Carens',
            ],

            'Nissan' => [
                'Altima', 'Sentra', 'Maxima', 'Micra', 'Versa', 'Rogue', 'X-Trail',
                'Pathfinder', 'Navara', 'Patrol', 'Murano',
            ],

            'Lexus' => [
                'ES', 'IS', 'GS', 'LS', 'NX', 'RX', 'GX', 'LX', 'UX',
            ],

            'Ford' => [
                'Fiesta', 'Focus', 'Fusion', 'Escape', 'Edge', 'Explorer',
                'Expedition', 'Mustang', 'Ranger', 'F-150',
            ],

            'Chevrolet' => [
                'Spark', 'Malibu', 'Impala', 'Camaro', 'Colorado', 'Silverado',
                'Tahoe', 'Suburban', 'Cruze', 'Traverse',
            ],

            'Peugeot' => [
                '206', '207', '301', '307', '308', '407', '508', '2008', '3008', '5008',
            ],

            'Renault' => [
                'Logan', 'Clio', 'Megane', 'Koleos', 'Duster', 'Captur', 'Kadjar',
            ],

            'Mazda' => [
                'Mazda2', 'Mazda3', 'Mazda6', 'CX-3', 'CX-5', 'CX-9', 'MX-5',
            ],

            'Subaru' => [
                'Impreza', 'Legacy', 'Outback', 'Forester', 'WRX', 'Crosstrek',
            ],

            'Mitsubishi' => [
                'Lancer', 'Mirage', 'Outlander', 'ASX', 'Pajero', 'Eclipse Cross',
            ],

            'Jeep' => [
                'Wrangler', 'Cherokee', 'Grand Cherokee', 'Compass', 'Renegade', 'Gladiator',
            ],

            'Land Rover' => [
                'Range Rover', 'Range Rover Sport', 'Range Rover Velar',
                'Discovery', 'Defender', 'Evoque',
            ],

            'Tesla' => [
                'Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck', 'Roadster',
            ],

        ];

        foreach ($makers as $maker => $models) {
            Maker::factory()
                ->state(['name' => $maker])
                ->has(
                    CarModel::factory()
                        ->count(count($models))
                        ->sequence(
                            ...array_map(fn ($model) => ['name' => $model], $models)
                        )
                )
                ->create();
        }

        User::factory()
            ->count(2)
            ->create();

        // User::factory()
        //     ->count(2)
        //     ->has(
        //         Car::factory()
        //             ->count(50)
        //             ->has(
        //                 CarImage::factory()
        //                     ->count(5)
        //                     ->sequence(fn (Sequence $sequence) => ['position' => $sequence->index + 1]),
        //                     'images'
        //             )
        //             ->hasFeatures(),
        //             'favouriteCars'
        //     )
        //     ->create();
        // User::factory()
        // ->count(2)
        // ->has(
            //     Car::factory()
            //         ->count(50)
            //         ->has(
            //             CarImage::factory()
            //                 ->count(5)
            //                 ->sequence(fn (Sequence $sequence) => ['position' => $sequence->index % 5 + 1]),
            //             'images'
            //         )
            //         ->hasFeatures(), // only if this is a valid factory method
            //     'favouriteCars'
        // )
        // ->create();

        // User::factory()
        // ->count(5)
        // ->create()
        // ->each(function ($user) {
        //     // Create 50 cars per user
        //     Car::factory()
        //         ->count(50)
        //         ->for($user, 'user') // associate with the user
        //         ->has(
        //             CarImage::factory()
        //                 ->count(5)
        //                 ->sequence(fn (Sequence $sequence) => ['position' => $sequence->index % 5 + 1]),
        //             'images'
        //         )
        //         ->hasFeatures() // only if this is valid in your CarFactory
        //         ->create();
        // });

        // Create 5 users
        User::factory()
            ->count(3)
            ->create()
            ->each(function ($owner) {
                // Create 50 cars for each user
                Car::factory()
                    ->count(30)
                    ->for($owner, 'owner')
                    ->has(
                        CarImage::factory()
                            ->count(5)
                            ->sequence(fn (Sequence $sequence) => ['position' => $sequence->index % 5 + 1]),
                        'images'
                    )
                    ->hasFeatures() // only if this exists in your CarFactory
                    ->create()
                    ->each(function ($car) use ($owner) {
                        // Randomly mark some cars as favourite for this user
                        if (rand(0, 1)) {
                            FavouriteCar::firstOrCreate([
                                'user_id' => $owner->id,
                                'car_id'  => $car->id,
                            ]);
                        }
                    });
            });

    }
}
