<?php
include 'connect.php';
/**
 * Returns:
 * $this->_data, $this->_links, $this->_showperpage
 */
class displayer{
    public
        $_targetpage, // current filename
        $_limit, 
        $_stages, 
        $_currentpage, // $_GET['page']
        $_start, 
        $_data, 
        $_links, 
        $_showperpage,
        $_default_filter;
    
    /**
     * @param array $collumn
     * @param array/string $tables
     * @param array/string $join_cols join collumn variable
     * @param array/string $join_col_vals join collumn value
     * @param string $search contains $_GET['search'] variable
     * @param string $sort    ''    $_GET['sort']   ''
     * @param array/string $searchvar, if greater than 1 array else string
     * @param array/string $sortvar, if greater than 1 array else string
     * Return $this->_data
     */
    public function data(
        $collumn, 
        $tables,
        $join_col,
        $join_col_val, 
        $search, 
        $sort, 
        $searchvar, 
        $sortvar
    ) {
    global $conn;

    if ($collumn == 'all') {
        $collumn = '*';
    }
    if (count($tables) == 1) {
        $table = $tables;
    }

    // makes the join statement
    $join_stmt = '';
    if (count($tables) > 1 and !empty($join_col) and !empty($join_col_val)) {
        $join_tables = array();
        $a           = 0;
        for ($x=1; $x < count($tables); $x++) {
            $join_tables[$a] = "JOIN $tables[$x]";
            $a++;
        }
        // print_r($join_tables);

        $joined_cols = array();
        for ($y=0; $y < count($join_col); $y++) {
            $joined_cols[$y] = "ON $join_col[$y]=$join_col_val[$y]";
        }
        // print_r($joined_cols);
        
        for ($z=0; $z < count($join_tables); $z++) {
            $join_stmt .= " $join_tables[$z] $joined_cols[$z]";
        }

        $table = $tables[0];
    }
    // echo '<br>'.$join_stmt;
    // makes the search query statement
    $searchquery = '';
    if (!empty($search) and !empty($searchvar)) {

        // makes the query separated with 'OR'
        if (count($searchvar) > 1) {
            foreach ($searchvar as &$value) {
                $searchquery .= "$value LIKE :search OR ";
            }
            unset($value);
    
            $searchquery = substr_replace($searchquery, '', -4);

        } else {
            $searchquery .= "$searchvar LIKE :search";
        }

        $searchval = "$search%";
    }
    
    // makes the sort query statement
    // this was customize or can customize
    $sortquery = '';
    $sortval = '';
    if (!empty($sort) and !empty($sortvar)) {
        // this will pass in links function
        $sortval = $sort;

        if (is_string($sort)) {
            $sort = "'$sort'";
        }
        // makes the query separated with 'OR'
        if (count($sortvar) > 1) {
            foreach ($sortvar as &$value) {
                $sortquery .= "$value=$sort OR ";
            }
            unset($value);       
            $sortquery = substr_replace($sortquery, '', -4);
        } else {
            $sortquery = "$sortvar=$sort";
        }
    }
    // end sortquery

    // makes the filter statement
    $filter_stmt = '';
    if ($this->_default_filter) {
        $filter_stmt = " WHERE $this->_default_filter";
    }

    if (empty($sortquery) and !empty($searchquery)) {
        $filter_stmt = " WHERE $searchquery";
        if ($this->_default_filter) {
            $filter_stmt .= " AND $this->_default_filter";
        }
    }
    if (empty($searchquery) and !empty($sortquery)) {
        $filter_stmt = " WHERE $sortquery";
        if ($this->_default_filter) {
            $filter_stmt .= " AND $this->_default_filter";
        }
    }
    if (!empty($searchquery) and !empty($sortquery)) {
        if ($searchvar > 1) {
            $searchquery = "($searchquery)";
        }
        if ($sortvar > 1) {
            $sortquery = "($sortquery)";
        }

        $filter_stmt = " WHERE $searchquery AND $sortquery";
        if ($this->_default_filter) {
            $filter_stmt .= " AND $this->_default_filter";
        }
    }

    $sql   = "SELECT $collumn FROM $table$join_stmt$filter_stmt";
    // echo '<br>'.$sql;
    $query = $conn->prepare($sql);
    if (!empty($search)) {
        for ($x = 0; $x < count($searchvar); $x++){
            $query->bindValue(':search', $searchval);
        }
    }
    $query->execute();
    $rowcount = $query->rowCount();

    $this->links($search, $sortval, $rowcount);

    if ($this->_currentpage > 1) {
        $start = ($this->_currentpage - 1) * $this->_limit;
    } else {
        $start = 0;
    }
    $this->_start = $start;
    
    $this->showperpage($rowcount);

    // Get page data
    $sql2   = "$sql LIMIT $start, $this->_limit";
    // echo $sql2;
    $query1 = $conn->prepare($sql2);

    if (!empty($search)) {
        for ($x = 0; $x < count($searchvar); $x++){
            $query1->bindValue(':search', $searchval);
        }
    }
    $query1->execute();

    while ($row = $query1->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    $this->_data = !empty($data) ? $data : false;
    }

    /**
      * @param string $searchval contains $_GET['search'] variable
      * @param string $sortval   ''     $_GET['sort']    ''
      * Return $this->_links
      */
    public function links($searchval, $sortval, $rowcount) {
        // Initial page num setup
        if ($rowcount >= 1) {
            $prev             = $this->_currentpage - 1;
            $next             = $this->_currentpage + 1;
            $lastpage         = ceil($rowcount/$this->_limit);
            $secondtolastpage = $lastpage - 1;
            
            $pagelinks = '';

            if ($lastpage > 1) {
                // opening div for links
                $pagelinks .= "<ul class='pagination'>";
                // creates the previous link
                if ($this->_currentpage > 1) {
                    $pagelinks .= 
                        "<li>
                          <a href='$this->_targetpage?page=$prev'>
                            &laquo;
                          </a>
                        </li>";
                } else {
                    $pagelinks .= 
                        "<li class='disabled'>
                          <span>&laquo;</span>   
                        </li>";
                }
                // creates the page numbers
                // not enough pages to breaking it up?
                if ($lastpage < 6 + ($this->_stages * 2)) {
                    for ($pagenum = 1; $pagenum <= $lastpage; $pagenum ++) {
                        if ($pagenum == $this->_currentpage) {
                            $pagelinks .= 
                                "<li class='active'>
                                  <span>$pagenum</span>
                                </li>";
                        } else{
                            $pagelinks .= 
                                "<li>
                                  <a href=
                                  '$this->_targetpage?page=$pagenum'>
                                    $pagenum</a>
                                </li>";

                        }
                    }
                // enough pages to hide a few?
                } elseif ($lastpage > 5 + ($this->_stages * 2)) { 

                    // beginning only hide later pages
                    if ($this->_currentpage < 1 + ($this->_stages * 2)) {
                        for (
                            $pagenum=1; 
                            $pagenum < 4 + ($this->_stages * 2); 
                            $pagenum++
                        ) {
                            if ($pagenum == $this->_currentpage) {
                                $pagelinks .= 
                                    "<li class='active'>
                                      <span class='current'>$pagenum</span>
                                    </li>";
                            } else{
                                $pagelinks .= 
                                    "<li>
                                      <a href=
                                      '$this->_targetpage?page=$pagenum'>
                                        $pagenum</a>
                                    </li>";
                            }
                        }
                        $pagelinks .= "<li class='disabled'>
                                        <span style='color: #555;'>...</span></li>";
                        $pagelinks .= 
                            "<li>
                              <a href=
                                '$this->_targetpage?page=$secondtolastpage'> 
                                $secondtolastpage</a>
                            </li>";
                        $pagelinks .= 
                            "<li>
                              <a href=
                              '$this->_targetpage?page=$lastpage'>
                              $lastpage</a>
                            </li>";
                      
                    } elseif (
                          $lastpage - ($this->_stages * 2) > $this->_currentpage 
                          and $this->_currentpage > ($this->_stages * 2)
                      ){
                      // middle hide some front and some back
                      $pagelinks  .= 
                          "<li>
                            <a href='$this->_targetpage?page=1'>
                            1</a>
                          </li>";
                      $pagelinks  .= 
                          "<li>
                            <a href='$this->_targetpage?page=2'>
                            2</a>
                          </li>";
                      $pagelinks  .= "<li class='disabled'>
                                      <span style='color: #555;'>...</span></li>";

                      for (
                          $pagenum=$this->_currentpage - $this->_stages;
                          $pagenum <= $this->_currentpage + $this->_stages; 
                          $pagenum++
                      ) {
                          if ($pagenum == $this->_currentpage) {
                              $pagelinks .= 
                                  "<li class='active'>
                                    <span>$pagenum</span>
                                  </li>";
                          } else{
                              $pagelinks .= 
                                  "<li>
                                    <a href=
                                    '$this->_targetpage?page=$pagenum'>
                                    $pagenum</a>
                                  </li>";
                          }
                      }
                      $pagelinks .= "<li class='disabled'>
                                      <span style='color: #555;'>...</span></li>";
                      $pagelinks .= 
                          "<li>
                            <a href=
                            '$this->_targetpage?page=$secondtolastpage'>
                            $secondtolastpage</a>
                          </li>";
                      $pagelinks .= 
                          "<li>
                            <a href=
                            '$this->_targetpage?page=$lastpage'>
                            $lastpage</a>
                          </li>";
                    } else {
                        $pagelinks .= 
                            "<li>
                              <a href='$this->_targetpage?page=1'>
                              1</a>
                            </li>";
                        $pagelinks .= 
                            "<li>
                              <a href='$this->_targetpage?page=2'>
                              2</a>
                            </li>";
                        $pagelinks .= "<li class='disabled'>
                                        <span style='color: #555;'>...</span></li>";
                        for (
                            $pagenum=$lastpage - (2 + ($this->_stages * 2)); 
                            $pagenum <= $lastpage; 
                            $pagenum++
                        ) {
                            if ($pagenum == $this->_currentpage) {
                                $pagelinks .= 
                                    "<li class='active'>
                                      <span>$pagenum</span>
                                    </li>";
                            } else{
                                $pagelinks .= 
                                    "<li>
                                      <a href=
                                      '$this->_targetpage?page=$pagenum'>
                                      $pagenum</a>
                                    </li>";
                            }
                        }
                    }
                }
                // creates the next link
                if ($this->_currentpage < $pagenum - 1) {
                    $pagelinks .= 
                        "<li>
                          <a href='$this->_targetpage?page=$next'>
                          &raquo;
                          </a>
                        </li>";
                } else{
                    $pagelinks .= 
                      "<li class='disabled'>
                        <span>&raquo;</span>
                      </li>";
                }
                $pagelinks .= "</ul>";
                // end div for links
            }
            $this->_links = $pagelinks;
        }
    }
    /**
     * Return $this->_showperpage
     */
    public function showperpage($rowcount){
        if ($rowcount !== 0) {
            // pagination
            $start = $this->_start + 1;
            $end   = $this->_start + $this->_limit;
            if ($end > $rowcount) {
                $remainingpages = $rowcount - $this->_start;
                $end            = $this->_start + $remainingpages;  
            }

            $endresult = $end == $start ? '' : "-$end";
            if ($rowcount > 1) {
                $showperpage = 
                    "<form method='POST' class='form-inline'>
                      <input type='hidden' name='url' value=$this->_targetpage>
                      
                      <div class='form-group'>
                        <label for='perPage'>Show per page:</label>
                        <div class='input-group'>
                          <input type='number' name='perPage'
                            max='$rowcount' min='1' class='form-control'
                            placeholder='$this->_limit'>
                          <span class='input-group-btn'>
                            <button type='submit' name='btnperpage' 
                              class='btn btn-default'>
                              Submit</button>
                          </span>
                        </div>
                      </div>
                    </form>";
            } else {
                $showperpage = '';
            }
            // echo $start; 
            $this->_showperpage =
                $showperpage."<br>Showing $start$endresult of $rowcount";
        }
    }
} // closing brace for displayer class
    
    if (isset($_POST['btnperpage'])) {
        $oneMonth = 60*60*24*30;
        setcookie("perPage[$_POST[url]]", $_POST['perPage'], time()+$oneMonth);
        header("location:".basename($_SERVER['PHP_SELF']));
    }
?>
