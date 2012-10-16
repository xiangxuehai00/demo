<?php
//get_page_list(记录总数, 当前页码, 页记录数, 显示多少页码, 排序方式, 翻页链接, 链接中的锚点, 链接样式【暂不使用】)
function get_page_list($total_rows, $current_page, $page_size=10, $page_numbers=10, $sort='asc', $href_string='', $anchor='', $class='')
{
    $tag = 'li';
    $start_tag = "<{$tag}>";
    $end_tag = "</{$tag}>";
	$page_num_str = "";
    //total pages
    if($total_rows <= $page_size)
    {
        return '';
    }
    $pages = intval(($total_rows / $page_size));
    if($total_rows % $page_size > 0)
    {
        $pages ++;
    }
    
    //home_page & end_page
    $start = $current_page - intval($page_numbers / 2);
    $end = $current_page + intval($page_numbers / 2) - 1;
    if($start <= 0)
    {
        $start = 1;
        $end = $start + $page_numbers -1;
    }
    if($end > $pages)
    {
        $end = $pages;
        $start = $end >= $page_numbers ? $end - $page_numbers +1 : 1;
    }

    // current_url, remove page=1,2,3
    if($href_string == '')
    {
        $href_string = $_SERVER['PHP_SELF'] . '?';
        $params = $_SERVER['QUERY_STRING'];
        $params = str_replace('&amp;', '&', $params);
        $params_array = explode('&', $params);
        foreach($params_array as $param)
        {
            $index = strpos($param, '=');
            $key = substr($param, 0, $index);
            if($key != 'page')
            {
                $href_string .= $param . '&';
            }
        }
        $href_string = rtrim($href_string,'&');
    }
    
    // buile prev page
    if($current_page > 1)
    {
        $prev_page = $current_page -1 ;
        if($prev_page < 1)
        {
            $prev_page = 1;
        }
        $prev_page_str = "{$start_tag}<a href='{$href_string}&page={$prev_page}{$anchor}' title='上一页'>上一页</a>{$end_tag}";
    }
    else 
    {
        $prev_page_str = "{$start_tag}<a>上一页</a>{$end_tag}";
    }
    
    // build page
    if($sort=='asc') //正序页码
    {
        for($i=$start;$i<=$end;$i++)
        {
            if($i == $current_page)
            {
                $page_num_str .= "<{$tag} class='on'><a>{$i}</a></{$tag}>";
            }
            else
            {
                $page_num_str .= "{$start_tag}<a href='{$href_string}&page={$i}{$anchor}'>{$i}</a>{$end_tag}";
            }
        }
    }
    else            //反序页码
    {
        for($i=$end;$i>=$start;$i--)
        {
            if($i == $current_page)
            {
                $page_num_str .= "<{$tag} class='on'><a>{$i}</a></{$tag}>";
            }
            else
            {
                $page_num_str .= "{$start_tag}<a href='{$href_string}&page={$i}{$anchor}'>{$i}</a>{$end_tag}";
            }
        }
    }

    if($current_page < $pages)
    {
        $next_page = $current_page + 1 ;
        if($next_page > $pages)
        {
            $next_page = $pages;
        }
        $next_page_str = "{$start_tag}<a href='{$href_string}&page={$next_page}{$anchor}'>下一页</a>{$end_tag}";
    }
    else 
    {
        $next_page_str = "{$start_tag}<a>下一页</a>{$end_tag}";
    }
                 
    if($sort == 'asc')
    {
        $page_list = $prev_page_str . $page_num_str . $next_page_str;
    }
    else
    {
        $page_list = $next_page_str . $page_num_str . $prev_page_str;
    }

    return $page_list;
}
