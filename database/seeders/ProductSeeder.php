<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Battery;
use App\Models\Cable;
use App\Models\Cart;
use App\Models\Inverter;
use App\Models\Load;
use App\Models\Panel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $file = new Filesystem;
       $file->cleanDirectory(storage_path('app/public/images/battery_images'));
       $file->cleanDirectory(storage_path('app/public/images/inverter_images'));
       $file->cleanDirectory(storage_path('app/public/images/load_images'));
       $file->cleanDirectory(storage_path('app/public/images/panel_images'));

       File::copy(public_path('images/fridge.webp'),storage_path('app/public/images/load_images/fridge.webp'));
       File::copy(public_path('images/washing_machine.jpg'),storage_path('app/public/images/load_images/washing_machine.jpg'));
       File::copy(public_path('images/laser_cooker.jpg'),storage_path('app/public/images/load_images/laser_cooker.jpg'));

       File::copy(public_path('images/inverter1.webp'),storage_path('app/public/images/inverter_images/inverter1.webp'));
       File::copy(public_path('images/inverter2.jpg'),storage_path('app/public/images/inverter_images/inverter2.jpg'));
       File::copy(public_path('images/inverter3.png'),storage_path('app/public/images/inverter_images/inverter3.png'));
       File::copy(public_path('images/agricultural_inverter.jpg'),storage_path('app/public/images/inverter_images/agricultural_inverter.jpg'));
       File::copy(public_path('images/inverter4.jpg'),storage_path('app/public/images/inverter_images/inverter4.jpg'));
       File::copy(public_path('images/industrial_inverter.jpg'),storage_path('app/public/images/inverter_images/industrial_inverter.jpg'));
       File::copy(public_path('images/inverter_household_Victron_Energy__11000.jpg'),storage_path('app/public/images/inverter_images/inverter_household_Victron_Energy__11000.jpg'));
       File::copy(public_path('images/houshold_Victron_Energy_3000.jpg'),storage_path('app/public/images/inverter_images/houshold_Victron_Energy_3000.jpg'));
       File::copy(public_path('images/household_PHASE PERFECT_1200.webp'),storage_path('app/public/images/inverter_images/household_PHASE PERFECT_1200.webp'));
       File::copy(public_path('images/household_EG4_3500.webp'),storage_path('app/public/images/inverter_images/household_EG4_3500.webp'));
       File::copy(public_path('images/household_SimpliPHI_6000.png'),storage_path('app/public/images/inverter_images/household_SimpliPHI_6000.png'));
       File::copy(public_path('images/household_Solis Inverters_5000.jpg'),storage_path('app/public/images/inverter_images/household_Solis Inverters_5000.jpg'));
       File::copy(public_path('images/household_SUNGOLDPOWER_2500.webp'),storage_path('app/public/images/inverter_images/household_SUNGOLDPOWER_2500.webp'));
       File::copy(public_path('images/household_SUNGOLDPOWER_6000.webp'),storage_path('app/public/images/inverter_images/household_SUNGOLDPOWER_6000.webp'));
       File::copy(public_path('images/household_SUNGOLDPOWER_15000.webp'),storage_path('app/public/images/inverter_images/household_SUNGOLDPOWER_15000.webp'));
       File::copy(public_path('images/household_Morningstar_3000.jpg'),storage_path('app/public/images/inverter_images/household_Morningstar_3000.jpg'));
       File::copy(public_path('images/agricultural_Jntech_750.jpg'),storage_path('app/public/images/inverter_images/agricultural_Jntech_750.jpg'));
       File::copy(public_path('images/agricultural_Jntech_1500.jpg'),storage_path('app/public/images/inverter_images/agricultural_Jntech_1500.jpg'));
       File::copy(public_path('images/agricultural_Jntech_2200.jpg'),storage_path('app/public/images/inverter_images/agricultural_Jntech_2200.jpg'));
       File::copy(public_path('images/agricutural_INGECON_4000.png'),storage_path('app/public/images/inverter_images/agricutural_INGECON_4000.png'));
       File::copy(public_path('images/Spc_agricutural_5500.jpg'),storage_path('app/public/images/inverter_images/Spc_agricutural_5500.jpg'));
       File::copy(public_path('images/agricultural_MPP_SOLAR_2200.webp'),storage_path('app/public/images/inverter_images/agricultural_MPP_SOLAR_2200.webp'));
       File::copy(public_path('images/agricultural_POWMr_7500.webp'),storage_path('app/public/images/inverter_images/agricultural_POWMr_7500.webp'));
       File::copy(public_path('images/agricultural_Gronius_22000.webp'),storage_path('app/public/images/inverter_images/agricultural_Gronius_22000.webp'));
       File::copy(public_path('images/agricultural_Expert_Power_37000.webp'),storage_path('app/public/images/inverter_images/agricultural_Expert_Power_37000.webp'));
       File::copy(public_path('images/agricultural_perfect_suitor_5500.webp'),storage_path('app/public/images/inverter_images/agricultural_perfect_suitor_5500.webp'));
       File::copy(public_path('images/agricultural_MPPT_4000.webp'),storage_path('app/public/images/inverter_images/agricultural_MPPT_4000.webp'));
       File::copy(public_path('images/industrial_Pure_Sine_Wave_125000.jpeg'),storage_path('app/public/images/inverter_images/industrial_Pure_Sine_Wave_125000.jpeg'));
       File::copy(public_path('images/industrial_Yaskawa_Solectria_Solar_137000.jpg'),storage_path('app/public/images/inverter_images/industrial_Yaskawa_Solectria_Solar_137000.jpg'));
       File::copy(public_path('images/industrial_Fortress_Power_60000.jpg'),storage_path('app/public/images/inverter_images/industrial_Fortress_Power_60000.jpg'));
       File::copy(public_path('images/industrial_EG4_60000.webp'),storage_path('app/public/images/inverter_images/industrial_EG4_60000.webp'));
       File::copy(public_path('images/industrial_LPS_300000.jpg'),storage_path('app/public/images/inverter_images/industrial_LPS_300000.jpg'));
       File::copy(public_path('images/industrial_Briggs_&_Stratton_600000.png'),storage_path('app/public/images/inverter_images/industrial_Briggs_&_Stratton_600000.png'));
       File::copy(public_path('images/industrial_Kaco_300000.png'),storage_path('app/public/images/inverter_images/industrial_Kaco_300000.png'));
       
       File::copy(public_path('images/battery1.jpg'),storage_path('app/public/images/battery_images/battery1.jpg'));
       File::copy(public_path('images/battery2.webp'),storage_path('app/public/images/battery_images/battery2.webp'));
       File::copy(public_path('images/battery3.jpg'),storage_path('app/public/images/battery_images/battery3.jpg'));

       File::copy(public_path('images/panel1.jpg'),storage_path('app/public/images/panel_images/panel1.jpg'));
       File::copy(public_path('images/panel2.jpg'),storage_path('app/public/images/panel_images/panel2.jpg'));
       File::copy(public_path('images/panel3.jpg'),storage_path('app/public/images/panel_images/panel3.jpg'));

       
       


       Load::insert([[
        'load' => 'electric laser cooker',
        'watt' => 400,
        'description' => 'be carful with this element it require big value of capacity',
        'photo' => '/storage/images/load_images/laser_cooker.jpg',
       ],
       [
        'load' => 'fridge',
        'watt' => 350,
        'description' => '',
        'photo' => '/storage/images/load_images/fridge.webp',
       ],
       [
        'load' => 'washing machine',
        'watt' => 500,
        'description' => '',
        'photo' => '/storage/images/load_images/washing_machine.jpg',
       ]]);


        Inverter::insert([[
        'type' => 'household',
        'manufacture_company' => 'VEVOR',
        'model' => '3KVA 2400W',
        'watt' => 1200,
        'description' => 'VEVOR Hybrid Solar Inverter, 3KVA 2400W, Pure Sine Wave Off-Grid Inverter, 24VDC to 110VAC Multi-Function Inverter with Build-in 50A PWM Solar Charge Controller, Support Utility/Generator/Solar Energy',
        'price' => 315,
        'quantity_available' => 50,
        'photo' => '/storage/images/inverter_images/inverter1.webp',
        ],
        [
            'type' => 'household',
            'manufacture_company' => 'Liniotech',
            'model' => 'LNT-H-8KLB-US',
            'watt' => 2500,
            'description' => 'Liniotech Split Phase Max 12KVA PV Input 8Kw Solar Inverter Hybrid Solar Power System',
            'price' => 370,
            'quantity_available' => 50,
            'photo' => '/storage/images/inverter_images/inverter2.jpg',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'SolarEdge',
                'model' => 'Home Wave Inverter',
                'watt' => 6000,
                'description' => 'The SolarEdge single phase inverter with Home Wave technology breaks the mold of traditional solar inverters. Winner of the prestigious 2016 Intersolar Award and the renowned 2018 Edison Award, the single phase inverter is specifically designed to work with SolarEdge power optimizers. It comes with a built-in DC safety switch, integrated rapid shutdown, and features a standard 12-year warranty extendable to 20 or 25 years.',
                'price' => 450,
                'quantity_available' => 50,
                'photo' => '/storage/images/inverter_images/inverter3.png',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'istock',
                'model' => 'Home Wave Inverter',
                'watt' => 4500,
                'description' => 'The SolarEdge single phase inverter with Home Wave technology breaks the mold of traditional solar inverters. Winner of the prestigious 2016 Intersolar Award and the renowned 2018 Edison Award, the single phase inverter is specifically designed to work with SolarEdge power optimizers. It comes with a built-in DC safety switch, integrated rapid shutdown, and features a standard 12-year warranty extendable to 20 or 25 years.',
                'price' => 450,
                'quantity_available' => 50,
                'photo' => '/storage/images/inverter_images/inverter4.jpg',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'Victron Energy',
                'model' => 'SCC145110510',
                'watt' => 11000,
                'description' => 'The SmartSolar MPPT RS solar charge controllers are Victron solution for systems with large series connected PV arrays charging 48 V DC battery banks. This product is perfect for large off-grid, and grid connected battery systems.',
                'price' => 530,
                'quantity_available' => 20,
                'photo' => '/storage/images/inverter_images/inverter_household_Victron_Energy__11000.jpg',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'Victron Energy',
                'model' => 'SCC125110512',
                'watt' => 3000,
                'description' => 'The MPPT VE.Can SmartSolar charger uses the latest and fastest technology to convert energy from a solar array into energy that optimally charges a battery banks.The SmartSolar charge controller will even recharge a severely depleted battery. It can operate with a battery voltage as low as 0 Volts, provided the cells are not permanently sulphated or otherwise damaged.',
                'price' => 210,
                'quantity_available' => 60,
                'photo' => '/storage/images/inverter_images/houshold_Victron_Energy_3000.jpg',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'PHASE PERFECT',
                'model' => 'NEMA 1 Standard',
                'watt' => 1200,
                'description' => 'Three-phase power from a single-phase source anywhere you need it. The Simple line of digital phase converters in an entry-level phase converter designed for non-industrial applications with mild starting loads, or loads that will tolerate voltage imbalance during motor startup',
                'price' => 110,
                'quantity_available' => 70,
                'photo' => '/storage/images/inverter_images/household_PHASE PERFECT_1200.webp',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'EG4',
                'model' => '3.5kW Off-Grid Inverter',
                'watt' => 3500,
                'description' => 'Maximize your off-grid solar power system with the EG4 3.5kW Off-Grid Inverter. Engineered for reliability and efficiency, this inverter delivers 3500 watts of continuous power output, ensuring seamless operation of your essential appliances and devices. With a robust design and advanced features, it can handle up to 5000 watts of photovoltaic (PV) input and 500 volts open circuit (VOC) input, making it suitable for a wide range of solar panel configurations. Whether you are powering a remote cabin, RV, or backup power system, the EG4 3kW Off-Grid Inverter provides dependable performance to keep your electricity flowing.',
                'price' => 3310,
                'quantity_available' => 8,
                'photo' => '/storage/images/inverter_images/household_EG4_3500.webp',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'SimpliPHI',
                'model' => '6kW Hybrid Inverter',
                'watt' => 6000,
                'description' => 'The SimpliPHI 6kW Hybrid Inverter is an energy-efficient and user-friendly option designed to work seamlessly with the SimpliPHI 4.9 kWh Battery. It’s engineered and built in California to connect with solar, generator, or utility inputs, whether in AC or DC setups, making it suitable for various home or business needs—whether you’re connected to the grid or not.',
                'price' => 4610,
                'quantity_available' => 10,
                'photo' => '/storage/images/inverter_images/household_SimpliPHI_6000.png',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'Solis Inverters',
                'model' => 'Solis-1P5K-4G-US-RSS',
                'watt' => 5000,
                'description' => 'Solis 6-10KW US version single phase string inverters have up to 4 MPPTs with 30K Hz switching frequency and ultra-high efficiency which are perfect for residential rooftop systems with different roof orientations. Multiple communication methods greatly ensure the freedom of monitoring. Built-in MLRSD transmitter guarantees the safety of relevant personnel and regulation compliance. The built-in class 0.5 revenue grade meter can guarantee an accurate data recording without losing benefits from the sun.',
                'price' => 3010,
                'quantity_available' => 15,
                'photo' => '/storage/images/inverter_images/household_Solis Inverters_5000.jpg',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'SUNGOLDPOWER',
                'model' => 'UL 2.5KW Hybrid Inverter',
                'watt' => 2500,
                'description' => '2.5KW Pure sine Wave Inverter 24V DC to AC 120V/240V, built in 4 MPPT Max 500VDC input each total Max 15000W PV input.',
                'price' => 310,
                'quantity_available' => 30,
                'photo' => '/storage/images/inverter_images/household_SUNGOLDPOWER_2500.webp',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'SUNGOLDPOWER',
                'model' => 'UL1741 Certified 6000W',
                'watt' => 6000,
                'description' => 'UL1741 Standard Solar Inverter: Sungoldpower 6.5KW DC 48V (SP6548 series) pure sine wave AC output 120V, Built-in MPPT solar charger max 120A and utility battery charger max 120A, Max PV input 390V (Voc) ,Dual PV input',
                'price' => 710,
                'quantity_available' => 35,
                'photo' => '/storage/images/inverter_images/household_SUNGOLDPOWER_6000.webp',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'SUNGOLDPOWER',
                'model' => '15000W 48V Split Phase',
                'watt' => 15000,
                'description' => 'LF-PV Series Pure Sine Wave Inverter is a combination of a 15000-watt inverter, AC charger, and Auto-transfer switch into one complete system.',
                'price' => 7110,
                'quantity_available' => 10,
                'photo' => '/storage/images/inverter_images/household_SUNGOLDPOWER_15000.webp',
            ],
            [
                'type' => 'household',
                'manufacture_company' => 'Morningstar',
                'model' => 'TS-MPPT-60-600V-24',
                'watt' => 3000,
                'description' => 'Morningstar’s TriStar TS-MPPT-60-600V-24 charge controller leverages Morningstar’s innovative TrakStar™ MPPT technology and our 20+ years of power electronics engineering excellence, to enable the widest input operating voltage range available from a solar array, wind turbine or hydro input. Rated for 60 Amps, the TriStar MPPT 600V controller does much more than efficiently charge batteries; it also provides remote communications, data logging, adjustability, and metering. In fact, the TriStar MPPT 600V controller is the only 600-volt PV controller to offer open communication protocols and true Ethernet-enabled functionality',
                'price' => 780,
                'quantity_available' => 20,
                'photo' => '/storage/images/inverter_images/household_Morningstar_3000.jpg',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'Jntech',
                'model' => 'JNP370L',
                'watt' => 750,
                'description' => '1.The product has strong overload capability and can drive three-phase AC water pump of the same power;
2.Adopting maximum power point tracking (MPPT) technology, efficiency >99%;
3.Wide range of MPPT input voltage ranges;
4.Outdoor use, protection grade IP65, adapt to harsh application environments;
5.RS485 and GPRS communication functions, remote monitoring and start-stop management through mobile APP;
6.Meet the simultaneous input of the utility grid/DG and photovoltaic, automatic switching, online energy complementary, photovoltaic priority, keep the pump rated work, and achieve 24H water supply.
7.Perfect system protection, undervoltage, overload, overvoltage, overcurrent, grid phase loss, pump dry, phase loss, short circuit, overheating, etc.
8.Fully automatic operation control, soft start and soft stop,unattended.',
                'price' => 4500,
                'quantity_available' => 40,
                'photo' => '/storage/images/inverter_images/agricultural_Jntech_750.jpg',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'Jntech',
                'model' => 'JNP11KH~JNP15K5H',
                'watt' => 1500,
                'description' => 'Solar pump inverter obtains direct current energy from photovoltaic cells, and converts it into electric energy to drive the water pump. According to the intensity of sunlight, take use of MPPT algorithm, the inverter adjusts the output frequency to make maximum use of solar energy.',
                'price' => 450,
                'quantity_available' => 30,
                'photo' => '/storage/images/inverter_images/agricultural_Jntech_1500.jpg',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'Jntech',
                'model' => 'BLDC Solar Pump Solu',
                'watt' => 2200,
                'description' => 'Solar  pumping inverter obtains DC electric energy from the photovoltaic cell and converts it into electric energy
to drive the water pump. According to the intensity of sunlight, the inverter adopts MPPT algorithm to adjust the
output frequency and make maximum use of solar energy.
This series solar  pumping inverter is a flexible, easy-to use and economical solar  pumping inverter launched
by Days energy according to the differentiated needs of customers. It aims to provide customers with economic
and comprehensive economic benefit solutions.',
                'price' => 10000,
                'quantity_available' => 50,
                'photo' => '/storage/images/inverter_images/agricultural_Jntech_2200.jpg',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'INGECON',
                'model' => '330-350TL M12',
                'watt' => 4000,
                'description' => 'The INGECON SUN 330-350TL M12 is a string inverter with 12 MPPTs to connect the strings from the PV modules, thanks to which, it can offer the best performance and lower capital expenditures (CAPEX), as there is no need to use DC combiner boxes',
                'price' => 250,
                'quantity_available' => 16,
                'photo' => '/storage/images/inverter_images/agricutural_INGECON_4000.png',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'SPC',
                'model' => '1pH 8pH 7.5kw 5.5kw 60kw',
                'watt' => 5500,
                'description' => 'Agricultural Irrigation 1pH 3pH 7.5kw 5.5kw 60kw MPPT Function Solar Pumping Inverter',
                'price' => 550,
                'quantity_available' => 35,
                'photo' => '/storage/images/inverter_images/Spc_agricutural_5500.jpg',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'MPP SOLAR',
                'model' => 'New',
                'watt' => 2200,
                'description' => 'Solar pump inverter 2200w LS Single 3 phase MPPT PV input max 450V 50hz 60hz',
                'price' => 250,
                'quantity_available' => 10,
                'photo' => '/storage/images/inverter_images/agricultural_MPP_SOLAR_2200.webp',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'POWMr',
                'model' => 'Phase MPPT Controller',
                'watt' => 7500,
                'description' => '3KW 5KW 10KW Solar Hybrid Inverter Off Grid 110/220V Split Phase MPPT Controller',
                'price' => 650,
                'quantity_available' => 10,
                'photo' => '/storage/images/inverter_images/agricultural_POWMr_7500.webp',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'Gronius',
                'model' => 'MPPT 208/240VAC',
                'watt' => 22000,
                'description' => 'Fronius Primo 5.0-1 Solar Inverter Single Phase -2 MPPT 208/240VAC',
                'price' => 1300,
                'quantity_available' => 50,
                'photo' => '/storage/images/inverter_images/agricultural_Gronius_22000.webp',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'Expert Power',
                'model' => 'W/120A MPPT solar charger & Wi-Fi',
                'watt' => 37000,
                'description' => 'ExpertPower 37000w 48v Off-grid Hybrid Inverter W/120A MPPT solar charger & Wi-Fi',
                'price' => 1450,
                'quantity_available' => 25,
                'photo' => '/storage/images/inverter_images/agricultural_Expert_Power_37000.webp',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'perfect suitor',
                'model' => '12/24V 100A MPPT',
                'watt' => 5500,
                'description' => '12/24V 100A MPPT Solar Charge Controller Panel Battery Regulator Dual USB Timer',
                'price' => 2450,
                'quantity_available' => 10,
                'photo' => '/storage/images/inverter_images/agricultural_perfect_suitor_5500.webp',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'MPPT',
                'model' => '12/24V 60/80/100A MPPT',
                'watt' => 4000,
                'description' => '12/24V 60/80/100A MPPT Solar Charge Controller Panel Battery Regulator Dual USB',
                'price' => 3450,
                'quantity_available' => 10,
                'photo' => '/storage/images/inverter_images/agricultural_MPPT_4000.webp',
            ],
            [
                'type' => 'agricultural',
                'manufacture_company' => 'istock',
                'model' => 'agricultural Inverter',
                'watt' => 4000,
                'description' => 'The SolarEdge single phase inverter with Home Wave technology breaks the mold of traditional solar inverters. Winner of the prestigious 2016 Intersolar Award and the renowned 2018 Edison Award, the single phase inverter is specifically designed to work with SolarEdge power optimizers. It comes with a built-in DC safety switch, integrated rapid shutdown, and features a standard 12-year warranty extendable to 20 or 25 years.',
                'price' => 450,
                'quantity_available' => 50,
                'photo' => '/storage/images/inverter_images/agricultural_inverter.jpg',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'istock',
                'model' => 'industrial Inverter',
                'watt' => 60000,
                'description' => 'The SolarEdge single phase inverter with Home Wave technology breaks the mold of traditional solar inverters. Winner of the prestigious 2016 Intersolar Award and the renowned 2018 Edison Award, the single phase inverter is specifically designed to work with SolarEdge power optimizers. It comes with a built-in DC safety switch, integrated rapid shutdown, and features a standard 12-year warranty extendable to 20 or 25 years.',
                'price' => 1000,
                'quantity_available' => 50,
                'photo' => '/storage/images/inverter_images/industrial_inverter.jpg',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'Pure Sine Wave',
                'model' => '125kW Pure Sine Wave',
                'watt' => 125000,
                'description' => '3 phase 4 wire power inverter is a pure sine wave off grid inverter with low price. This solar power inverter with low frequency 50Hz/ 60Hz, 100kW high power output rating, no battery storage system, transforms 480V DC to 400V/ 460V AC (input and output voltage are customizable), high efficiency and stable performance. 100 kW off grid pv inverter is widely used in CNC machine, emergency car and compressor.',
                'price' => 2000,
                'quantity_available' => 30,
                'photo' => '/storage/images/inverter_images/industrial_Pure_Sine_Wave_125000.jpeg',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'Yaskawa Solectria Solar',
                'model' => 'UUX002283',
                'watt' => 137000,
                'description' => 'The XGI 1500-250 and XGI 1500-200 feature SiC technology, high power and high efficiency that places them at the top end of the utility-scale string inverters in the market.
Yaskawa Solectria Solar designs all XGI 1500 utility-scale string inverters for high reliability and builds them with the highest quality components -- selected, tested and proven to last beyond their warranty. The XGI 1500 inverters provide advanced grid-support functionality and meet the latest IEEE 1547 and UL 1741 standards for safety.',
                'price' => 2500,
                'quantity_available' => 5,
                'photo' => '/storage/images/inverter_images/industrial_Yaskawa_Solectria_Solar_137000.jpg',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'Fortress Power',
                'model' => 'Envy60KW-Evault',
                'watt' => 60000,
                'description' => 'Solar hybrid Envy inverter (8 kW Continuous, 12 kW Peak, 13 kW Max PV Input) LFP battery ,360 AH, 18.5 KWh total capacity, LCD display; 10 yr. warranty',
                'price' => 1500,
                'quantity_available' => 15,
                'photo' => '/storage/images/inverter_images/industrial_Fortress_Power_60000.jpg',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'EG4',
                'model' => 'EG4 60KPV Hybrid Inverter',
                'watt' => 60000,
                'description' => 'EG4 60KPV Hybrid Inverter All-In-One Solar Inverter | 60000W PV Input | 12000W Output | 48V 120/240V Split Phase | EG4-18KPV-12LV ',
                'price' => 2500,
                'quantity_available' => 25,
                'photo' => '/storage/images/inverter_images/industrial_EG4_60000.webp',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'LPS',
                'model' => 'BL-LPS-1150',
                'watt' => 300000,
                'description' => 'LPS inverter emergency power system, 300000 watts, 120/277VAC, UL listed for damp locations.
Midsize-electrical inverter systems for powering up to 750 to 300000 watts of incandescent, fluorescent, induction or LED lighting loads. Pulse width modulated (PWM) output design provides clean, 60 Hz. sinusoidal emergency power to loads.',
                'price' => 4500,
                'quantity_available' => 25,
                'photo' => '/storage/images/inverter_images/industrial_LPS_300000.jpg',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'Briggs & Stratton',
                'model' => '600kWh AccESS',
                'watt' => 600000,
                'description' => 'Briggs & Stratton 600kWh AccESS system features 6 PHI 3.8kWh batteries (also available with 3 batteries) for a total of 600kWh of energy storage. The batteries are paired with a SimpliPHI 6kW Hybrid Inverter for an all-in-one energy storage and management solution that comes fully integrated and pre-programmed in a NEMA 3R rated enclosure for outdoor installations. With smart communication between the inverter and batteries, this system can self-monitor, provide backup power to essential loads, and help reduce utility bills.',
                'price' => 5000,
                'quantity_available' => 30,
                'photo' => '/storage/images/inverter_images/industrial_Briggs_&_Stratton_600000.png',
            ],
            [
                'type' => 'industrial',
                'manufacture_company' => 'Kaco',
                'model' => 'blueplanet 100 NX3 / 125 NX3',
                'watt' => 300000,
                'description' => 'String inverters for commercial and industrial PV systems: 87.0 / 92.0 / 105 / 110 US / 125 US ',
                'price' => 4000,
                'quantity_available' => 20,
                'photo' => '/storage/images/inverter_images/industrial_Kaco_300000.png',
            ]]);


        Battery::insert([[
        'type' => 'lithium',
        'manufacture_company' => 'Banshee',
        'model' => 'MBB-LFP12-100',
        'ampere' => 100,
        'volt' => 12,
        'description' => 'Banshee 12V 100Ah LifePO4 lithium battery is the best choice for having the power you need wherever life takes you. With 2x the power of regular lead acid batteries, 50% of the weight, more than 5x the recharges (2500+ cycles), and up to 5x the calendar life, this battery is an easy solution for many applications!',
        'price' => 200,
        'quantity_available' => 50,
        'photo' => '/storage/images/battery_images/battery1.jpg',
        ],
        [
            'type' => 'gel',
            'manufacture_company' => 'Regony',
            'model' => '1A-52',
            'ampere' => 100,
            'volt' => 12,
            'description' => 'Rechargeable Deep Cycle Hybrid Gel Battery for Solar Wind RV Marine Camping UPS Wheelchair Trolling Motor, Maintenance Free, Non Spillable',
            'price' => 300,
            'quantity_available' => 50,
            'photo' => '/storage/images/battery_images/battery2.webp',
            ],
            [
                'type' => 'tubular',
                'manufacture_company' => 'UB4D',
                'model' => 'UPG #45965 UB-4D 12V 200Ah',
                'ampere' => 200,
                'volt' => 12,
                'description' => 'Dimensions: 20.75 inches x 8.11 inches x 9.65 inches. Weight: 114.60 Lbs SLA/AGM maintenance free, spill proof battery
                 Rechargeable battery that can be mounted in any position, resists shocks and vibration 1 Year Warranty
                 Report an issue with this product or seller, Note: Products with electrical plugs are designed for use in the US. Outlets and voltage differ internationally and this product may require an adapter or converter for use in your destination. Please check compatibility before purchasing.',
                'price' => 300,
                'quantity_available' => 50,
                'photo' => '/storage/images/battery_images/battery3.jpg',
                ]]);

        
        Panel::insert([[
            'type' => 'Solar Panel',
            'manufacture_company' => 'Anker',
            'model' => 'IP67 Waterproof',
            'watt' => 200,
            'width' => 100,
            'hight' => 200,
            'description' => 'Anker 531 Solar Panel, 200W Foldable Portable Solar Charger, IP67 Waterproof, 23% Higher Energy Conversion Efficiency, Smart Sunlight Alignment via Suncast, for Camping, RV (Only for 767 PowerHouse)',
            'price' => 550,
            'quantity_available' => 50,
            'photo' => '/storage/images/panel_images/panel1.jpg',
        ],
        [
            'type' => 'Solar Panel',
            'manufacture_company' => 'Renogy',
            'model' => '200-Watt 12-Volt Monocrystalline',
            'watt' => 200,
            'width' => 100,
            'hight' => 130,
            'description' => '200-Watt 12-Volt Monocrystalline Solar Panel for Off Grid Large System Residential Commercial House Cabin Sheds Rooftop',
            'price' => 220,
            'quantity_available' => 50,
            'photo' => '/storage/images/panel_images/panel2.jpg',
        ],
        [
            'type' => 'Solar Panel',
            'manufacture_company' => 'Newpowa',
            'model' => 'Newpowa 9BB Cell 100W Monocrystalline',
            'watt' => 100,
            'width' => 100,
            'hight' => 100,
            'description' => 'Newpowa 9BB Cell 100W Monocrystalline 100W 12V Solar Panel and Mounting Z Bracket with Nuts and Bolts Supporting for RV, Boat, Wall, Off Grid Roof Installation',
            'price' => 85,
            'quantity_available' => 50,
            'photo' => '/storage/images/panel_images/panel3.jpg',
        ]]);


        





    }
}
