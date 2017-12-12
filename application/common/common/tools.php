<?php
namespace app\common\common;

use think\Log;

// 公用函数库
class Tools
{
    public static function makePage($total, $page, $page_size)
    {
        if ($total != null && $page != null && $page_size != null) {
            // 计算总共有多少页
          $page_num = ceil($total/$page_size);

            $loop_num = 5;
            $loop_start = 1;
          // 如果没5页，就只显示这几页
          if ($page_num < 5) {
              $loop_num = $page_num;
          } else {
              // 如果有5页以上，那就显示5页
              // 如果有当前页如果在3页以上，并且在最后2页之前，则显示正负两页
              if ($page > 3) {
                  if ($page < $page_num - 2) {
                      $loop_start = $page - 2;
                  } else {
                      $loop_start = $page_num - 4;
                  }
              }
          }

          // 翻页前置数据
          $result = '<nav aria-label="Page navigation" style="text-align: center;"><ul class="pagination">';
            $result = $result . '<li><a href="?page=1" aria-label="Previous"><span>&laquo;</span></a></li>';

          // 翻页图中间的数据
          for ($i = $loop_start; $i < $loop_start + $loop_num; $i++) {
              if ($i == $page) {
                  $result = $result . '<li class="active"><a href="?page=' . $i . '"><span>' . $i . '</span></a></li>';
              } else {
                  $result = $result . '<li><a href="?page=' . $i . '"><span>' . $i . '</span></a></li>';
              }
          }
          // $result = $result . '<li><a href="?page=' . ($page-1) . '"><span>' . ($page-1). '</span></a></li>';
          // $result = $result . '<li class="active"><a href="?page=' . $page . '"><span>' . $page . '</span></a></li>';
          // $result = $result . '<li><a href="?page=' . ($page+1) .'"><span>' . ($page+1). '</span></a></li>';

          $result = $result . '<li><a href="?page=' . $page_num . '"  aria-label="Next"><span>&raquo;</span></a></li>';
            $result = $result . '<li><span>共' . $page_num . '页</span></a></li>';
            // $result = $result . '<li><span>共' . $total . '条数据</span></a></li>';
            $result = $result . '</ul></nav>';
        } else {
            trace("makePage empty param");
            return "<alert>翻页图生成失败</alert>";
        }
        return $result;
    }


    public static function makePageUrl($prefix, $total, $page, $page_size)
    {
        if ($total != null && $page != null && $page_size != null) {
            $page_num = ceil($total/$page_size);

            $loop_num = 5;
            $loop_start = 1;

            if ($page_num < 5) {
                $loop_num = $page_num;
            } else {
                if ($page > 3) {
                    if ($page < $page_num - 2) {
                        $loop_start = $page - 2;
                    } else {
                        $loop_start = $page_num - 4;
                    }
                }
            }

            $result = '<nav aria-label="Page navigation" style="text-align: center;"><ul class="pagination">';
            $result = $result . '<li><a href="?' . $prefix . '&page=1" aria-label="Previous"><span>&laquo;</span></a></li>';

            for ($i = $loop_start; $i < $loop_start + $loop_num; $i++) {
                if ($i == $page) {
                    $result = $result . '<li class="active"><a href="?'. $prefix . '&page=' . $i . '"><span>' . $i . '</span></a></li>';
                } else {
                    $result = $result . '<li><a href="?' . $prefix . '&page=' . $i . '"><span>' . $i . '</span></a></li>';
                }
            }

            $result = $result . '<li><a href="?' . $prefix . '&page=' . $page_num . '" aria-label="Next"><span>&raquo;</span></a></li>';
            $result = $result . '<li><span>共' . $page_num . '页</span></a></li>';
            $result = $result . '</ul></nav>';
        } else {
            trace("makePage empty param");
            return "<alert>翻页图生成失败</alert>";
        }
        return $result;
    }
}
