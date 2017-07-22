<?php
ob_start();
require_once ('pdf/fpdf.php');
require_once ('pdf/fpdi.php');
if (isset($_POST['submit']))
{
$pageNumbers = $_POST['pageNumbers'];
$file = $_FILES['file'];
$fileName = $_FILES['file']['name'];
$fileTmpName = $_FILES['file']['tmp_name'];
$fileSize = $_Files['file']['size'];
$fileError = $_FILES['file']['error'];
$fileType = $_FILES['file']['type'];
$fileEXT = explode('.', $fileName);
$fileActualExt = strtolower(end($fileEXT));
$allowed = array('pdf');
if (in_array($fileActualExt, $allowed))
{
if ($fileError === 0)
{
if ($fileSize < 1000000)
{
$fileDestination = 'styles/'.$fileName;
move_uploaded_file($fileTmpName, $fileDestination);
$pdf = new FPDI();
$pageCount = $pdf->setSourceFile($fileDestination);
$skipPages = preg_split('/\,/',$pageNumbers);
//  Add all pages of source to new document
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++)
{
//  Skip undesired pages
if (in_array($pageNo, $skipPages)) continue;
//  Add page to the document
$templateID = $pdf->importPage($pageNo);
$pdf->getTemplateSize($templateID);
$pdf->addPage();
$pdf->useTemplate($templateID);
}
$modifiedFile = uniqid().'_'.$fileName;
$pdf->output($modifiedFile,F);
//header('Location: https://gabehcoud.000webhostapp.com/');die();
// We'll be outputting a PDF
header('Content-Type: application/pdf');
// It will be called downloaded.pdf
header('Content-Disposition: inline; filename="transformed.pdf"');
// The PDF source is in original.pdf
readfile($modifiedFile);
}
else
{
//echo "Max allowed file size is 1GB";
}
}
else
{
//echo "There was an error uploading your file";
}
}
else
{
//echo "You cannot upload files of this type!";
}
}
ob_end_flush();
?>