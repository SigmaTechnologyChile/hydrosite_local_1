<!DOCTYPE html>
<html>

<head>
    <title>Impresión de DTEs</title>
    <style>
        .boleta-web-iframe {
            width: 100%;
            height: 1200px;
            border: none;
        }

        @media print {
            @page {
                size: A2 portrait;
                margin: 0;
                padding: 0;
            }

            .boleta-iframe-container {
                width: 410mm;
                height: 584mm;
                max-height: 584mm;
                padding: 5mm;
                margin: 5mm auto;
            }

            @page {
                size: A3 portrait;
                margin: 0;
                padding: 0;
            }

            .boleta-iframe-container {
                width: 287mm;
                height: 410mm;
                max-height: 410mm;
                padding: 5mm;
                margin: 5mm auto;
            }

            @page {
                size: A4 portrait;
                margin: 0;
                padding: 0;
            }

            .boleta-iframe-container {
                width: 200mm;
                height: 287mm;
                max-height: 287mm;
                padding: 5mm;
                margin: 5mm auto;
            }


            @page {
                size: A5 portrait;
                margin: 0;
                padding: 0;
            }

            .boleta-iframe-container {
                width: 138mm;
                height: 200mm;
                max-height: 200mm;
                padding: 2mm;
                margin: 5mm auto;
            }

            @page {
                size: A6 portrait;
                margin: 0;
                padding: 0;
            }

            .boleta-iframe-container {
                width: 97mm;
                height: 140mm;
                max-height: 140mm;
                padding: 2mm;
                margin: 4mm auto;
            }

        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iframes = document.querySelectorAll('iframe');
            let loadedCount = 0;

            iframes.forEach(iframe => {
                iframe.onload = function () {
                    try {
                        const iframeDoc = iframe.contentWindow.document;
                        const body = iframeDoc.body;
                        const html = iframeDoc.documentElement;

                        const height = Math.max(
                            body.scrollHeight, body.offsetHeight,
                            html.clientHeight, html.scrollHeight, html.offsetHeight
                        );

                        iframe.style.height = (height + 20) + 'px';

                    } catch (e) {
                        iframe.style.height = 'auto';
                    } finally {
                        loadedCount++;
                        if (loadedCount === iframes.length) {
                            window.print();
                        }
                    }
                };

                setTimeout(() => {
                    loadedCount++;
                    if (loadedCount === iframes.length) {
                        window.print();
                    }
                }, 5000);
            });

            if (iframes.length === 0) {
                window.print();
            }
        });
    </script>
</head>

<body>
@php use Illuminate\Support\Str; @endphp

@foreach($order_items as $item)
    @php
        $readingId = $item->reading_id;
        $tipoDTE = strtolower(trim($item->type_dte));

    @endphp

    @switch($tipoDTE)
        @case('boleta')
            <iframe src="{{ route('orgs.multiBoletaPrint', ['id' => $org->id, 'reading_id' => $readingId]) }}"
                style="width: 100%; border: none;"></iframe>
                <div style="page-break-after: always;"></div>
            @break

        @case('factura')
            <iframe src="{{ route('orgs.multiFacturaPrint', ['id' => $org->id, 'reading_id' => $readingId]) }}"
                style="width: 100%; border: none;"></iframe>
                <div style="page-break-after: always;"></div>
            @break

        @default
            <p>No se puede mostrar el DTE ({{ $tipoDTE }})</p>
    @endswitch
@endforeach

</body>

</html>
