<?php
declare(strict_types=1);
/**
 * Copyright (c) 2015 - 2018 Paragon Initiative Enterprises <https://paragonie.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *******************************************************************************
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 - 2018 Paragon Initiative Enterprises
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 * If you would like to use this library under different terms, please
 * contact Paragon Initiative Enterprises to inquire about a license exemption.
 */

use ParagonIE\AntiCSRF\AntiCSRF;
use PHPUnit\Framework\TestCase;

/**
 * These are the tests for the anti-csrf class.
 */
class AntiCSRFTest extends TestCase
{

    /**
     * @covers AntiCSRF::insertToken()
     */
    public function testInsertToken()
    {
        $post = [];
        $session = [];
        $server = $_SERVER;

        $csrft = new AntiCSRF($post, $session, $server);
        $token_html = $csrft->insertToken('', false);
        
        $idx = $csrft->getSessionIndex();
        $this->assertFalse(
            empty($csrft->session[$idx])
        );

        $this->assertFalse(
            empty($session[$idx])
        );

        $this->assertContains("<input", $token_html);
    }

    /**
     * @covers AntiCSRF::getTokenArray()
     */
    public function testGetTokenArray()
    {
        @session_start();

        try {
            $csrft = new AntiCSRF();
        } catch (Throwable $ex) {
            $post = [];
            $session = [];
            $server = $_SERVER;

            $csrft = new AntiCSRF($post, $session, $server);
        }
        $result = $csrft->getTokenArray();

        $this->assertFalse(
            empty($csrft->session[$csrft->getSessionIndex()])
        );
        $this->assertSame( 
            [
                $csrft->getFormIndex(),
                $csrft->getFormToken(),
            ], 
            array_keys($result)
        );
    }
}
