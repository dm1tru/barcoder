<?php

namespace Dm1tru\Barcoder\Infrastructure;

use Dm1tru\Barcoder\Domain\Entity\Barcode;
use Dm1tru\Barcoder\Domain\Repository\BarcodeRepositoryInterface;
use Dm1tru\Barcoder\Domain\ValueObject\Code;
use Dm1tru\Barcoder\Domain\ValueObject\Count;
use Dm1tru\Barcoder\Domain\ValueObject\Date;
use Dm1tru\Barcoder\Domain\ValueObject\Id;
use PDO;
use PDOStatement;

class MysqlBarcodeRepository implements BarcodeRepositoryInterface
{
    private PDO $pdo;
    private PDOStatement $_qInsert;
    private PDOStatement $_qGetAll;
    private PDOStatement $_qGetAfterDate;

    public function __construct()
    {
        $this->connect();

        $this->_qInsert = $this->pdo->prepare(
            "INSERT INTO barcodes 
            SET code = :code, count = :count, date = FROM_UNIXTIME(:date), device_id = :device_id"
        );

        $this->_qGetAll = $this->pdo->prepare(
            "SELECT id, count, code, UNIX_TIMESTAMP(date) as date, device_id
            FROM barcodes
            ORDER BY id 
            LIMIT :limit OFFSET :offset"
        );

        $this->_qGetAfterDate = $this->pdo->prepare(
            "SELECT id, count, code, UNIX_TIMESTAMP(date) as date, device_id
            FROM barcodes
            WHERE date >= FROM_UNIXTIME(:date)
            ORDER BY id LIMIT :limit OFFSET :offset"
        );
    }

    private function connect(): void
    {
        $conf = parse_ini_file('config.ini', true);
        $dsn = "mysql:host=$conf[MYSQL_HOST];dbname=$conf[MYSQL_DATABASE]";
        $this->pdo = new \PDO($dsn, $conf['MYSQL_USER'], $conf['MYSQL_PASSWORD']);
    }

    public function add(Barcode $barcode): Barcode
    {
        $this->_qInsert->execute([
            'code' => $barcode->getCode()->getCode(),
            'count' => $barcode->getCount()->getCount(),
            'date' => $barcode->getDate()->getTimestamp(),
            'device_id' => $barcode->getDeviceId()->getId()
        ]);
        $id = new Id($this->pdo->lastInsertId());

        return new Barcode($id, $barcode->getDeviceId(), $barcode->getCode(), $barcode->getCount(), $barcode->getDate());
    }

    public function getAll(int $limit = 100, int $offset = 0): array
    {
        $barcodes = [];
        $this->_qGetAll->bindParam(':limit', $limit, PDO::PARAM_INT);
        $this->_qGetAll->bindParam(':offset', $offset, PDO::PARAM_INT);
        $this->_qGetAll->execute();
        while ($ret = $this->_qGetAll->fetch(PDO::FETCH_ASSOC)) {
            $barcodes[] = new Barcode(
                new Id($ret['id']),
                new Id($ret['devive_id']),
                new Code($ret['code']),
                new Count($ret['count']),
                new Date($ret['date'])
            );
        }

        return $barcodes;
    }

    public function getAfterDate(Date $date, int $limit = 100, int $offset = 0): array
    {
        $barcodes = [];
        $ts = $date->getTimestamp();
        $this->_qGetAfterDate->bindParam(':date', $ts);
        $this->_qGetAfterDate->bindParam(':limit', $limit, PDO::PARAM_INT);
        $this->_qGetAfterDate->bindParam(':offset', $offset, PDO::PARAM_INT);
        $this->_qGetAfterDate->execute();
        while ($ret = $this->_qGetAfterDate->fetch(PDO::FETCH_ASSOC)) {
            $barcodes[] = new Barcode(
                new Id($ret['id']),
                new Id($ret['device_id']),
                new Code($ret['code']),
                new Count($ret['count']),
                new Date($ret['date'])
            );
        }

        return $barcodes;
    }
}
