<?php
class Data
{
    /**

     * 获取指定belong下的值

     * @param $belong 要获取的项名

     * @return array|string|mixed
     */
    public function get($belong = '', $idx = NULL, $ShowIdx = false)
    {
        global $DATABASE;
        if (!empty($idx)) {
            $stmt = $DATABASE->prepare("SELECT content FROM miku_data WHERE belong = :belong AND idx = :idx ORDER BY idx");
            $stmt->bindParam(':belong', $belong);
            $stmt->bindParam(':idx', $idx);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
        } else {
            if ($ShowIdx === false) {
                $stmt = $DATABASE->prepare("SELECT idx, content, time FROM miku_data WHERE belong = :belong ORDER BY idx");
                $stmt->bindParam(':belong', $belong);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    $text = [];
                    foreach ($result as $row) {
                        $text[$row["idx"]] = $row["content"];
                    }
                    return $text;
                }
            } else {
                $stmt = $DATABASE->prepare("SELECT idx, content, des, time FROM miku_data WHERE belong = :belong ORDER BY idx");
                $stmt->bindParam(':belong', $belong);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    $text = [];
                    foreach ($result as $row) {
                        $text[$row["idx"]] = [$row["content"], $row["des"]];
                    }
                    return $text;
                }
            }
        }
        return [];
    }

    /**

     * 设置指定belong下的值

     * @param $belong 要设置的项名
     * @param $data 键值数组对
     * @param $des 键说明，可选

     * @return void
     */
    public function set($belong, $data, $des = "")
    {
        global $DATABASE;
        foreach ($data as $idx => $value) {
            $stmt = $DATABASE->prepare("REPLACE INTO miku_data (idx, content, des, belong) VALUES (:idx, :value, :des, :belong)");
            $stmt->bindParam(':idx', $idx);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':des', $des);
            $stmt->bindParam(':belong', $belong);
            $stmt->execute();
        }
    }

    /**

     * 更新指定belong下的值

     * @param $belong 要更新的项名
     * @param $data 要更新的键值对

     * @return void
     */
    public function update($belong, $data)
    {
        global $DATABASE;
        $stmt = $DATABASE->prepare("UPDATE miku_data SET content = :value WHERE idx = :idx AND belong = :belong");

        foreach ($data as $idx => $value) {
            $stmt->bindParam(':idx', $idx);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':belong', $belong);
            $stmt->execute();
        }
    }

    /**

     * 删除指定belong下的值

     * @param $belong 要删除的项名
     * @param $idxs 要删除的键，支持列表，留空删除全部

     * @return void
     */
    public function delete($belong, $idxs = [])
    {
        global $DATABASE;

        if (isset($idxs)) {
            foreach ($idxs as $idx) {
                $stmt = $DATABASE->prepare("DELETE FROM miku_data WHERE idx = :idx AND belong = :belong");
                $stmt->bindParam(':idx', $idx);
                $stmt->bindParam(':belong', $belong);
                $stmt->execute();
            }
        } else {
            $stmt = $DATABASE->prepare("DELETE FROM miku_data WHERE belong = :belong");
            $stmt->bindParam(':belong', $belong);
            $stmt->execute();
        }
    }
}
