    @extends('principal')
    @section('contenido')


    @if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
            <inicioadmin :ruta="ruta"></inicioadmin>
            </template>
                <template v-if="menu==1">
                <transferiradmin :ruta="ruta"></transferiradmin>
                </template>
                <template v-if="menu==2">
                <ingreso :ruta="ruta"></ingreso>
                </template>

                <template v-if="menu==3">
                    <bodega :ruta="ruta"></bodega>
                </template>

                <template v-if="menu==4">
                <usuario :ruta="ruta"></usuario>
                </template>
            @elseif (Auth::user()->idrol == 2)

            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
            <inicio :ruta="ruta"></inicio>
            </template>
                <template v-if="menu==1">
                <transferir :ruta="ruta"></transferir>
                </template>
                <template v-if="menu==2">
                <ingreso :ruta="ruta"></ingreso>
                </template>
            @else

            @endif

        @endif



        
    @endsection