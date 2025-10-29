


<div class="flex flex-1 justify-center items-center p-4">

    <div class="grid grid-cols-2 bg-white w-full h-full p-4 rounded-md gap-4 border border-gray-300 shadow-xl shadow-amber-100 ">

        <form class="border border-gray-500 rounded bg-gray-100 hover:bg-emerald-100 hover:border-emerald-500 group" action="?route=libros/seleccionar-tipo" method="POST">
            <input type="hidden" name="action" value="DefinirTipolibro">
            <input type="hidden" name="tipo" value="Bautizos">
            <button type="submit" class="w-full cursor-pointer relative flex flex-col justify-between items-center p-2 rounded-md">
                <span class="font-bold text-2xl text-center group-hover:text-emerald-600">Bautizos</span>
                <svg class="w-60" xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 512 512">
                    <path d="M90.3 1.4c-19.8 4.8-34 21.5-36.5 42.8-1.5 13.4-.5 31.9 2.2 39 2.9 7.9 9.6 15 17.8 19l6.7 3.3h43l5.7-2.8c7.4-3.6 13.9-10.2 17.5-17.5 2.6-5.4 2.8-6.5 3.1-23 .3-13.3 0-18.9-1.2-24.5-5.8-26-32.4-42.5-58.3-36.3M236 13.5V27h-27v30h27v66h30l.2-32.8.3-32.7 13.8-.3 13.8-.3-.3-14.7-.3-14.7-13.7-.3-13.8-.3V0h-30zM391.1 1c-13.1 2.8-26.5 13.1-32.5 25.2-5 9.8-5.8 15.3-5.4 35.6.3 16.7.4 18 3 23.4 3.4 7.3 9.8 13.7 17.4 17.4l5.9 2.9h43l6.7-3.3c7.4-3.6 13.2-9.5 16.9-17 2.2-4.4 2.4-6.1 2.7-24.4.3-16.2 0-20.8-1.3-25.5-4.4-15.1-16.2-27.6-31-32.7C410.6.5 397.3-.3 391.1 1M90.3 138.4c-14.9 3.8-27.3 14.1-34.1 28.3l-3.7 7.8-.3 44.2-.3 44.3H152v-40.3c0-44-.4-47.6-5.9-58.5-5.6-11.1-15.3-19.6-27.6-24.1-7.5-2.8-20.8-3.6-28.2-1.7m302.7-.7c-5.1.8-14.7 4.7-19.8 8-10.3 6.9-18.6 19-21.1 31.1-.7 3.2-1.1 17.4-1.1 35.7V243h100v-31.3c0-33.9-.5-37.7-5.6-48-1.5-2.8-5.7-8.2-9.3-11.8-11.6-11.6-27.2-16.8-43.1-14.2m-153.5 45.6c-11.9 4-21.6 12.4-26.8 23.4l-3.2 6.8v18c0 17.6.1 18.1 2.7 23.1 3.2 6 9.3 11.8 15.7 14.8 4.2 1.9 6.3 2.1 23.6 2.1 18.8 0 19.1 0 24.7-2.8 6.5-3.2 12.1-9 15.1-15.6 3-6.4 3.7-29.7 1.3-39.2-3.3-12.7-11.5-22.5-23.5-28.2-5.4-2.6-7.8-3.1-16-3.4-6.2-.2-11 .2-13.6 1m110.1 94.9c-.6 5-7.6 107.9-7.6 110.5 0 1 12.5 1.3 59 1.3h59l-.1-4.8c0-2.6-1.7-28.8-3.7-58.2l-3.7-53.5-51.2-.3-51.2-.2zm-147.6 39c0 31.4.5 35.4 6.3 46.2 6 11.4 18 20.6 31.2 24.1 26.3 6.8 53.8-10.1 60-36.8 1.2-5.3 1.5-12.2 1.3-34.1l-.3-27.4-6.1 3.7c-11.6 7-17.5 8.3-39 8.9-14.9.3-20.7.1-26.7-1.2-7.6-1.5-17.4-5.5-23.4-9.6l-3.3-2.2zM52 402.5V512h100V293H52zm317.7 18.2c-.4.3-.7 21-.7 46V512h64v-92h-31.3c-17.3 0-31.7.3-32 .7" />
                </svg>
            </button>
        </form>

        <form class="border border-gray-500 rounded bg-gray-100 hover:bg-emerald-100 hover:border-emerald-500 group" method="POST">
            <input type="hidden" name="action" value="DefinirTipolibro">
            <input type="hidden" name="tipo" value="Confirmaciones">
            <button type="submit" class="w-full cursor-pointer relative flex flex-col justify-between items-center p-2 rounded-md">
                <span class="font-bold text-2xl text-center group-hover:text-emerald-600">Confirmaciones</span>
                <svg class="w-60" xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                        <path d="M1350 4944 c-283 -34 -512 -115 -720 -257 -109 -75 -303 -273 -379 -386 -110 -167 -187 -353 -228 -556 -27 -137 -24 -425 6 -565 61 -286 182 -529 406 -815 350 -448 1093 -1176 1983 -1943 l143 -124 67 58 c646 561 962 852 1397 1288 451 451 702 746 857 1006 320 536 315 1156 -13 1651 -81 122 -273 315 -391 393 -177 116 -376 197 -573 232 -136 24 -376 24 -505 -1 -264 -50 -530 -177 -705 -338 l-60 -56 40 -32 c78 -62 213 -207 270 -288 l58 -82 41 38 c22 21 68 64 101 95 l60 58 63 -39 c294 -181 483 -490 522 -855 11 -95 3 -257 -16 -332 l-6 -24 156 0 c142 0 156 -2 156 -18 0 -9 -7 -73 -15 -142 -47 -407 -178 -695 -429 -945 -204 -203 -423 -319 -722 -381 -113 -24 -153 -27 -319 -28 -144 -1 -212 4 -280 17 -298 59 -545 188 -744 388 -199 199 -329 444 -396 745 -14 65 -35 148 -46 184 -64 204 -211 375 -406 473 l-52 27 25 92 c80 303 283 521 554 595 91 24 300 24 390 -1 76 -21 185 -74 253 -122 l47 -34 34 37 c18 21 140 160 271 308 l237 270 -59 53 c-174 160 -427 283 -680 332 -100 19 -320 33 -393 24z" />
                        <path d="M2359 3943 l-206 -236 91 -117 90 -118 222 217 223 216 -32 58 c-40 73 -153 217 -170 216 -6 0 -105 -107 -218 -236z" />
                        <path d="M2785 3493 c-246 -241 -452 -440 -458 -442 -18 -7 -161 147 -280 304 -284 375 -455 481 -698 435 -116 -22 -230 -105 -294 -213 l-28 -48 88 -81 c191 -177 281 -348 351 -663 57 -258 143 -429 297 -592 289 -304 712 -412 1165 -297 385 97 680 390 791 785 11 41 21 78 21 82 0 4 -93 7 -206 7 -161 0 -205 3 -201 13 3 6 31 68 62 136 80 175 98 247 97 401 0 152 -21 243 -87 377 -41 82 -147 233 -165 233 -4 0 -209 -197 -455 -437z" />
                    </g>
                </svg>
            </button>
        </form>

        <form class="border border-gray-500 rounded bg-gray-100 hover:bg-emerald-100 hover:border-emerald-500 group" method="POST">
            <input type="hidden" name="action" value="DefinirTipolibro">
            <input type="hidden" name="tipo" value="Defunciones">
            <button type="submit" class="w-full cursor-pointer relative flex flex-col justify-between items-center p-2 rounded-md">
                <span class="font-bold text-2xl text-center group-hover:text-emerald-600">Defunciones</span>
                <svg class="w-60" viewBox="0 0 24 24" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg">
                    <title />
                    <path d="M19,20H5V9a7,7,0,0,1,7-7h0a7,7,0,0,1,7,7Z" style="fill:#b3c1c9" />
                    <path d="M5,18H19a2,2,0,0,1,2,2v2a0,0,0,0,1,0,0H3a0,0,0,0,1,0,0V20A2,2,0,0,1,5,18Z" style="fill:#738394" />
                    <rect height="2" style="fill:#fff" width="8" x="8" y="8" />
                    <rect height="2" style="fill:#fff" width="8" x="8" y="12" />
                </svg>
            </button>
        </form>

        <form class="border border-gray-500 rounded bg-gray-100 hover:bg-emerald-100 hover:border-emerald-500 group" method="POST">
            <input type="hidden" name="action" value="DefinirTipolibro">
            <input type="hidden" name="tipo" value="Matrimonios">
            <button type="submit" class="w-full cursor-pointer relative flex flex-col justify-between items-center p-2 rounded-md">
                <span class="font-bold text-2xl text-center group-hover:text-emerald-600">Matrimonios</span>
                <svg class="w-60" xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                        <path d="M2044 5106 c-293 -67 -491 -368 -430 -656 33 -158 57 -191 350 -492 145 -150 339 -347 430 -438 l166 -165 156 155 c86 85 279 282 429 436 289 297 311 327 351 460 26 85 24 220 -4 309 -120 389 -584 529 -904 273 l-28 -22 -27 22 c-53 42 -128 82 -193 103 -83 27 -215 33 -296 15z" />
                        <path d="M1465 3298 c-173 -19 -382 -82 -547 -164 -178 -89 -314 -191 -464 -346 -402 -418 -550 -1024 -388 -1586 157 -543 593 -978 1137 -1136 319 -93 662 -85 977 21 483 162 880 560 1040 1042 105 317 117 635 35 951 -29 112 -88 260 -103 260 -20 0 -122 -64 -181 -113 -108 -91 -201 -238 -236 -376 -9 -35 -21 -127 -26 -205 -5 -78 -15 -170 -23 -206 -85 -391 -388 -707 -774 -806 -366 -94 -743 18 -1010 301 -133 141 -212 286 -263 481 -34 129 -34 359 0 488 102 391 381 670 778 778 63 17 108 21 239 22 l161 1 44 65 c93 136 216 271 353 386 l49 42 -79 26 c-232 76 -477 102 -719 74z" />
                        <path d="M3277 3300 c-711 -90 -1265 -588 -1426 -1280 -58 -250 -50 -554 20 -810 34 -121 82 -240 97 -240 26 0 134 72 199 134 106 98 182 226 218 366 9 35 21 129 26 210 13 212 54 354 147 512 303 516 974 672 1477 344 219 -143 373 -361 447 -636 32 -115 31 -362 -1 -484 -51 -194 -134 -345 -267 -485 -215 -227 -502 -344 -803 -328 l-106 5 -45 -66 c-91 -135 -218 -274 -354 -389 l-49 -41 94 -30 c594 -190 1234 -36 1674 403 305 304 471 677 492 1105 21 442 -143 875 -454 1200 -253 264 -565 432 -918 495 -106 19 -371 27 -468 15z" />
                    </g>
                </svg>
            </button>
        </form>

    </div>

</div>