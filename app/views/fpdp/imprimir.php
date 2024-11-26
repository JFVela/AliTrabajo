<?php
// Incluye la librería FPDF
require('../fpdp/fpdf.php');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "audio_system";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el valor de la acción desde la URL
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// Verificar que la acción sea válida y manejarla
if ($accion == 'imprimirCliente') {
    // Obtener el ID del cliente desde la URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        die("ID no válido.");
    }

    // Consultar datos del cliente
    $query = "SELECT * FROM clientes WHERE id_cliente = $id";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        die("Cliente no encontrado.");
    }

    $cliente = $result->fetch_assoc();

    // Consultar las cotizaciones del cliente
    $query_cotizaciones = "SELECT * FROM cotizaciones WHERE id_cliente = $id";
    $result_cotizaciones = $conn->query($query_cotizaciones);

    // Consultar las reservas relacionadas con las cotizaciones del cliente, uniendo la tabla distritos
    $query_reservas = "
    SELECT r.id_reserva, r.idDist, r.direccion, r.fecha_reserva, r.hora_reserva, r.ampm, r.telefonoContacto, r.estado_reserva, d.Distrito
    FROM reservas r
    INNER JOIN distritos d ON r.idDist = d.idDist
    WHERE r.id_cotizacion IN (SELECT id_cotizacion FROM cotizaciones WHERE id_cliente = $id)";
    $result_reservas = $conn->query($query_reservas);

    // Crear un nuevo PDF
    class PDF extends FPDF
    {
        // Cabecera del documento
        function Header()
        {
            // Logo de la empresa (ajusta la ruta según corresponda)
            $this->Image('logo.png', 180, 5, 20, 20, 'png');
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, utf8_decode('Información del Cliente'), 0, 1, 'C');
            $this->Ln(5);

            // Fecha de impresión en la esquina superior derecha
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, utf8_decode('Fecha de Impresión: ' . date('d/m/Y H:i:s')), 0, 0, 'R');
            $this->Ln(10);

            // Nombre de la empresa
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, utf8_decode('Empresa Falsa'), 0, 1, 'C');
            $this->Ln(10);
        }

        // Pie de página
        function Footer()
        {
            // Pie de página
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo()), 0, 0, 'C');
        }

        // Tabla personalizada
        function Table($header, $data, $is_reserva = false)
        {
            // Ancho de las columnas
            $w = $is_reserva ? [25, 35, 25, 50, 30, 25] : [40, 70, 40];
            $this->SetFont('Arial', 'B', 12);

            // Cabecera de la tabla con color de fondo
            $this->SetFillColor(144, 238, 144); // Verde claro
            for ($i = 0; $i < count($header); $i++) {
                $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
            }
            $this->Ln();

            // Datos de la tabla
            $this->SetFont('Arial', '', 12);
            foreach ($data as $row) {
                $this->Cell($w[0], 6, utf8_decode($row['id_cotizacion'] ?? $row['id_reserva']), 1);
                $this->Cell($w[1], 6, utf8_decode($row['fecha_cotizacion'] ?? $row['fecha_reserva']), 1);
                $this->Cell($w[2], 6, utf8_decode($row['total'] ?? $row['telefonoContacto']), 1);
                if ($is_reserva) {
                    $this->Cell($w[3], 6, utf8_decode($row['Distrito']), 1);
                    $this->Cell($w[4], 6, utf8_decode($row['hora_reserva'] . ' ' . $row['ampm']), 1);
                    $this->Cell($w[5], 6, utf8_decode($row['estado_reserva']), 1);
                }
                $this->Ln();
            }
        }
    }

    // Crear el objeto PDF
    $pdf = new PDF();
    $pdf->AddPage('P'); // Establecer orientación horizontal
    $pdf->SetFont('Arial', '', 12);

    // Agregar datos del cliente al PDF
    $pdf->Cell(0, 10, "ID: " . $cliente['id_cliente'], 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Nombre: " . $cliente['nombre_cliente']), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Teléfono: " . $cliente['telefono']), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Email: " . $cliente['email']), 0, 1);
    $pdf->Cell(0, 10, utf8_decode("Dirección: " . $cliente['direccion']), 0, 1);
    $pdf->Cell(0, 10, "DNI: " . $cliente['dni'], 0, 1);

    // Espacio
    $pdf->Ln(10);

    // Historia de cotizaciones
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Historia de Cotizaciones'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $header_cotizaciones = ['ID Cotización', 'Fecha Cotización', 'Total'];
    $cotizaciones_data = [];
    while ($cotizacion = $result_cotizaciones->fetch_assoc()) {
        $cotizaciones_data[] = $cotizacion;
    }
    $pdf->Table($header_cotizaciones, $cotizaciones_data);

    // Espacio
    $pdf->Ln(10);

    // Historia de reservas
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Historia de Alquileres'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $header_reservas = ['ID Reserva', 'Fecha Reserva', 'Telf.', 'Distrito', 'Hora Reserva', 'Estado'];
    $reservas_data = [];
    while ($reserva = $result_reservas->fetch_assoc()) {
        $reservas_data[] = $reserva;
    }
    $pdf->Table($header_reservas, $reservas_data, true);

    // Salida del PDF (para descargar el archivo)
    $pdf->Output('Cliente_' . $cliente['id_cliente'] . '.pdf', 'D');
}

// Verificar acción para imprimir detalles de la reserva
if ($accion == 'imprimirReserva') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        die("ID no válido.");
    }

    // Consultar datos de la reserva
    $query = "
        SELECT 
            r.id_reserva, r.direccion AS direccion_reserva, r.fecha_reserva,
            r.hora_reserva, r.ampm, r.telefonoContacto, r.estado_reserva,
            c.id_cotizacion, c.fecha_cotizacion, c.total, cl.nombre_cliente,
            cl.telefono AS telefono_cliente, cl.email,
            cl.direccion AS direccion_cliente, cl.dni, cp.capturaPago_url,
            cp.estadoPago, dp.metodoPago AS descripcionPago, dp.descripcionPago AS metodoPago,
            d.Distrito, p.Provincia, dept.Departamento
        FROM reservas r 
        JOIN cotizaciones c ON r.id_cotizacion = c.id_cotizacion 
        JOIN clientes cl ON c.id_cliente = cl.id_cliente 
        JOIN capturapago cp ON r.idCapt = cp.idCapt 
        JOIN descripcionpago dp ON cp.idPago = dp.idPago 
        JOIN distritos d ON r.idDist = d.idDist 
        JOIN provincias p ON d.idProv = p.idProv 
        JOIN departamentos dept ON p.idDepa = dept.idDepa 
        WHERE r.id_reserva = ?
    ";

    // Preparar y ejecutar consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Reserva no encontrada.");
    }

    $reserva = $result->fetch_assoc();

    // Crear PDF
    class PDF extends FPDF
    {
        function Header()
        {
            // Fecha en la esquina superior derecha
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 10, 'Fecha de Impresion: ' . date('d/m/Y'), 0, 0, 'R');
            $this->Ln(10);

            // Título centrado
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, utf8_decode('Detalle de Reserva'), 0, 1, 'C');
            $this->Ln(5);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
        }

        function TableSection($title, $data)
        {
            // Título de la sección
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, utf8_decode($title), 0, 1);
            $this->Ln(2);

            // Tabla
            $this->SetFont('Arial', '', 12);
            foreach ($data as $key => $value) {
                $this->Cell(50, 10, utf8_decode($key), 1);
                $this->Cell(0, 10, utf8_decode($value), 1, 1);
            }
            $this->Ln(5);
        }
    }

    // Crear el PDF
    $pdf = new PDF();
    $pdf->AddPage();

    // Información de la reserva
    $pdf->TableSection('Información de la Reserva', [
        'ID Reserva' => $reserva['id_reserva'],
        'Fecha de Reserva' => $reserva['fecha_reserva'],
        'Hora de Reserva' => $reserva['hora_reserva'] . ' ' . $reserva['ampm'],
        'Estado de Reserva' => $reserva['estado_reserva']
    ]);

    // Información del cliente
    $pdf->TableSection('Información del Cliente', [
        'Nombre del Cliente' => $reserva['nombre_cliente'],
        'Teléfono del Cliente' => $reserva['telefono_cliente'],
        'Email' => $reserva['email'],
        'Dirección del Cliente' => $reserva['direccion_cliente'],
        'DNI' => $reserva['dni']
    ]);

    // Información de la ubicación
    $pdf->TableSection('Información de Ubicación', [
        'Dirección de la Reserva' => $reserva['direccion_reserva'],
        'Distrito' => $reserva['Distrito'],
        'Provincia' => $reserva['Provincia'],
        'Departamento' => $reserva['Departamento']
    ]);

    // Información del pago
    $pdf->TableSection('Información del Pago', [
        'Método de Pago' => $reserva['metodoPago'],
        'Descripción del Pago' => $reserva['descripcionPago'],
        'Estado del Pago' => $reserva['estadoPago'],
        'Total Cotización' => $reserva['total']
    ]);

    // Salida del PDF
    $pdf->Output('Reserva_' . $reserva['id_reserva'] . '.pdf', 'D');
}

// Cerrar conexión
$conn->close();
