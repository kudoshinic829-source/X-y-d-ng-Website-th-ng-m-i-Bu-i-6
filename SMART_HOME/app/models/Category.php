<?php
class Category extends Model {
    protected static string $table = 'categories';
    public static function findBySlug(string $slug): ?array {
        $stmt = self::db()->prepare('SELECT * FROM categories WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]); $row = $stmt->fetch(); return $row ?: null;
    }
    public static function activeList(): array {
        return self::db()->query("SELECT * FROM categories WHERE status='active' ORDER BY name ASC")->fetchAll();
    }
    public static function countProducts(int $id): int {
        $s = self::db()->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
        $s->execute([$id]); return (int)$s->fetchColumn();
    }
    public static function slugify(string $name): string {
        $s = mb_strtolower(trim($name), 'UTF-8');
        $map = ['à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a','è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e','ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i','ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o','ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u','ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y','đ'=>'d'];
        $s = strtr($s, $map); return trim(preg_replace('/[^a-z0-9]+/', '-', $s), '-');
    }
}
